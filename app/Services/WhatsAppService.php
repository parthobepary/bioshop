<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;

    protected ?string $accessToken;

    protected ?string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = 'https://graph.facebook.com/v18.0';
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    /**
     * Whether the WhatsApp Business API credentials are present.
     *
     * The dashboard is reachable without them so sellers can read their
     * conversation history; only outbound calls need the credentials.
     */
    public function isConfigured(): bool
    {
        return filled($this->accessToken) && filled($this->phoneNumberId);
    }

    /**
     * Send a text message to a WhatsApp number
     */
    public function sendMessage(string $to, string $message, ?WhatsappConversation $conversation = null): ?array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ];

        $response = $this->makeRequest('POST', "/{$this->phoneNumberId}/messages", $payload);

        if ($response && isset($response['messages'][0]['id'])) {
            // Store outgoing message if conversation exists
            if ($conversation) {
                WhatsappMessage::create([
                    'conversation_id' => $conversation->id,
                    'wa_message_id' => $response['messages'][0]['id'],
                    'direction' => 'outgoing',
                    'sender_type' => 'ai',
                    'content' => $message,
                    'message_type' => 'text',
                    'status' => 'sent',
                ]);

                $conversation->update(['last_message_at' => now()]);
            }

            return $response;
        }

        return null;
    }

    /**
     * Send a message from the seller (manual reply)
     */
    public function sendSellerMessage(WhatsappConversation $conversation, string $message): ?array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($conversation->customer_phone),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ];

        $response = $this->makeRequest('POST', "/{$this->phoneNumberId}/messages", $payload);

        if ($response && isset($response['messages'][0]['id'])) {
            WhatsappMessage::create([
                'conversation_id' => $conversation->id,
                'wa_message_id' => $response['messages'][0]['id'],
                'direction' => 'outgoing',
                'sender_type' => 'seller',
                'content' => $message,
                'message_type' => 'text',
                'status' => 'sent',
            ]);

            $conversation->update(['last_message_at' => now()]);

            return $response;
        }

        return null;
    }

    /**
     * Send a product message
     */
    public function sendProductMessage(string $to, array $product, WhatsappConversation $conversation): ?array
    {
        $price = number_format($product['price'], 0);
        $message = "🛍️ *{$product['name']}*\n\n";
        $message .= "💰 মূল্য: ৳{$price}\n";

        if (!empty($product['description'])) {
            $message .= "\n📝 {$product['description']}\n";
        }

        if ($product['status'] === 'available') {
            $message .= "\n✅ স্টকে আছে";
        } elseif ($product['status'] === 'stock_out') {
            $message .= "\n❌ স্টক আউট";
        } elseif ($product['status'] === 'pre_order') {
            $message .= "\n⏳ প্রি-অর্ডার";
        }

        $message .= "\n\nঅর্ডার করতে \"অর্ডার\" লিখে পাঠান।";

        return $this->sendMessage($to, $message, $conversation);
    }

    /**
     * Send payment information
     */
    public function sendPaymentInfo(string $to, array $paymentMethods, WhatsappConversation $conversation): ?array
    {
        $message = "💳 *পেমেন্ট মেথড*\n\n";

        foreach ($paymentMethods as $method) {
            $type = strtoupper($method['type']);
            $message .= "📱 *{$type}*: {$method['account_number']}\n";
            if (!empty($method['account_name'])) {
                $message .= "   নাম: {$method['account_name']}\n";
            }
            $message .= "\n";
        }

        $message .= "পেমেন্ট করার পর ট্রানজেকশন ID পাঠান।";

        return $this->sendMessage($to, $message, $conversation);
    }

    /**
     * Process incoming webhook
     */
    public function processWebhook(array $data): void
    {
        if (!isset($data['entry'][0]['changes'][0]['value']['messages'])) {
            return;
        }

        $value = $data['entry'][0]['changes'][0]['value'];
        $messages = $value['messages'];
        $contacts = $value['contacts'] ?? [];

        foreach ($messages as $message) {
            $from = $message['from'];
            $messageId = $message['id'];
            $timestamp = $message['timestamp'];
            $type = $message['type'];

            // Get customer name from contacts
            $customerName = null;
            foreach ($contacts as $contact) {
                if ($contact['wa_id'] === $from) {
                    $customerName = $contact['profile']['name'] ?? null;
                    break;
                }
            }

            // Extract message content based on type
            $content = $this->extractMessageContent($message);

            // Find or create conversation
            $conversation = $this->findOrCreateConversation($from, $customerName);

            if ($conversation) {
                // Store incoming message
                $waMessage = WhatsappMessage::create([
                    'conversation_id' => $conversation->id,
                    'wa_message_id' => $messageId,
                    'direction' => 'incoming',
                    'sender_type' => 'customer',
                    'content' => $content,
                    'message_type' => $type,
                    'status' => 'delivered',
                ]);

                $conversation->update([
                    'last_message_at' => now(),
                    'unread_count' => $conversation->unread_count + 1,
                ]);

                // Trigger AI response
                $this->handleIncomingMessage($conversation, $waMessage);
            }
        }
    }

    /**
     * Extract message content based on type
     */
    protected function extractMessageContent(array $message): string
    {
        $type = $message['type'];

        return match ($type) {
            'text' => $message['text']['body'] ?? '',
            'image' => '[Image]',
            'video' => '[Video]',
            'audio' => '[Audio]',
            'document' => '[Document]',
            'location' => '[Location]',
            'contacts' => '[Contact]',
            'interactive' => $message['interactive']['button_reply']['title'] ?? $message['interactive']['list_reply']['title'] ?? '',
            default => '[Unknown message type]',
        };
    }

    /**
     * Find or create a conversation for the phone number
     */
    protected function findOrCreateConversation(string $phone, ?string $customerName): ?WhatsappConversation
    {
        // Find profile that this message is intended for
        // This would typically be determined by the WhatsApp Business Account setup
        // For now, we'll need to implement a way to route messages to the correct profile

        // Try to find existing conversation
        $conversation = WhatsappConversation::where('customer_phone', $phone)
            ->orderBy('last_message_at', 'desc')
            ->first();

        if ($conversation) {
            if ($customerName && !$conversation->customer_name) {
                $conversation->update(['customer_name' => $customerName]);
            }
            return $conversation;
        }

        // For new conversations, we need to determine which profile this is for
        // This is a simplified implementation - in production, you'd have more sophisticated routing
        // For now, return null if we can't determine the profile
        return null;
    }

    /**
     * Handle incoming message and trigger AI response
     */
    protected function handleIncomingMessage(WhatsappConversation $conversation, WhatsappMessage $message): void
    {
        $profile = $conversation->profile;
        $settings = $profile->whatsappSettings;

        // Check if AI is enabled
        if (!$settings || !$settings->ai_enabled || !$settings->auto_reply_enabled) {
            return;
        }

        // Check business hours
        if (!$settings->isWithinBusinessHours()) {
            if ($settings->away_message) {
                $this->sendMessage($conversation->customer_phone, $settings->away_message, $conversation);
            }
            return;
        }

        // Use AI to generate response
        $aiService = app(AIChatService::class);
        $response = $aiService->generateResponse($conversation, $message->content);

        if ($response) {
            $this->sendMessage($conversation->customer_phone, $response, $conversation);
        }
    }

    /**
     * Format phone number for WhatsApp API
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, assume Bangladesh number
        if (str_starts_with($phone, '0')) {
            $phone = '88' . $phone;
        }

        // If doesn't have country code, add Bangladesh code
        if (strlen($phone) === 10) {
            $phone = '880' . $phone;
        }

        return $phone;
    }

    /**
     * Make API request to WhatsApp Business API
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('WhatsApp API call skipped: credentials are not configured', [
                'endpoint' => $endpoint,
            ]);

            return null;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->{strtolower($method)}($this->apiUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('WhatsApp API Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verify webhook token
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        $verifyToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }

        return null;
    }
}
