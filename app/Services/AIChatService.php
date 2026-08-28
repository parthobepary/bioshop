<?php

namespace App\Services;

use App\Models\Product;
use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatService
{
    protected string $apiUrl = 'https://api.anthropic.com/v1/messages';
    protected string $apiKey;
    protected string $model = 'claude-3-haiku-20240307';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key');
    }

    /**
     * Generate AI response for incoming message
     */
    public function generateResponse(WhatsappConversation $conversation, string $message): ?string
    {
        $profile = $conversation->profile;
        $settings = $profile->whatsappSettings;

        // Build context
        $context = $this->buildContext($conversation, $profile);

        // Check for product queries
        $productMatch = $this->findProductMatch($profile, $message);

        // Check for FAQ match
        $faqMatch = $this->findFaqMatch($settings, $message);

        // Check for order intent
        $isOrderIntent = $this->detectOrderIntent($message);

        // Check for payment query
        $isPaymentQuery = $this->detectPaymentQuery($message);

        // Build the prompt
        $systemPrompt = $this->buildSystemPrompt($profile, $settings, $productMatch, $faqMatch);

        // Get conversation history
        $history = $this->getConversationHistory($conversation);

        // Call AI API
        $response = $this->callAI($systemPrompt, $history, $message);

        if (!$response) {
            // Fallback response
            return $this->getFallbackResponse($isOrderIntent, $isPaymentQuery, $productMatch);
        }

        return $response;
    }

    /**
     * Build context for the AI
     */
    protected function buildContext(WhatsappConversation $conversation, $profile): array
    {
        return [
            'shop_name' => $profile->name,
            'customer_name' => $conversation->customer_name,
            'products_count' => $profile->products()->where('status', 'available')->count(),
            'has_payment_methods' => $profile->paymentMethods()->where('is_active', true)->exists(),
        ];
    }

    /**
     * Find product match from message
     */
    protected function findProductMatch($profile, string $message): ?Product
    {
        $message = strtolower($message);

        // Search for product by name
        return $profile->products()
            ->where('status', 'available')
            ->where(function ($query) use ($message) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$message}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$message}%"]);
            })
            ->first();
    }

    /**
     * Find FAQ match
     */
    protected function findFaqMatch($settings, string $message): ?array
    {
        if (!$settings || !$settings->faq_items) {
            return null;
        }

        $message = strtolower($message);
        $keywords = [
            'delivery' => ['ডেলিভারি', 'delivery', 'কবে পাবো', 'কতদিন'],
            'price' => ['দাম', 'price', 'কত', 'মূল্য'],
            'payment' => ['পেমেন্ট', 'payment', 'বিকাশ', 'নগদ', 'রকেট', 'টাকা'],
            'return' => ['রিটার্ন', 'return', 'ফেরত', 'বদলানো'],
            'stock' => ['স্টক', 'stock', 'আছে', 'available'],
        ];

        foreach ($settings->faq_items as $faq) {
            $question = strtolower($faq['question']);

            // Direct match
            if (str_contains($message, $question) || str_contains($question, $message)) {
                return $faq;
            }

            // Keyword match
            foreach ($keywords as $category => $categoryKeywords) {
                foreach ($categoryKeywords as $keyword) {
                    if (str_contains($message, $keyword) && str_contains($question, $keyword)) {
                        return $faq;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Detect order intent
     */
    protected function detectOrderIntent(string $message): bool
    {
        $orderKeywords = [
            'অর্ডার', 'order', 'কিনব', 'কিনতে', 'চাই', 'নিব', 'দাও', 'দিও',
            'buy', 'purchase', 'want', 'need', 'get', 'বুক', 'book',
        ];

        $message = strtolower($message);

        foreach ($orderKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect payment query
     */
    protected function detectPaymentQuery(string $message): bool
    {
        $paymentKeywords = [
            'পেমেন্ট', 'payment', 'বিকাশ', 'bkash', 'নগদ', 'nagad',
            'রকেট', 'rocket', 'টাকা', 'পাঠাব', 'দিব', 'account',
            'নম্বর', 'number', 'কিভাবে', 'how',
        ];

        $message = strtolower($message);

        foreach ($paymentKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build system prompt for AI
     */
    protected function buildSystemPrompt($profile, $settings, ?Product $product, ?array $faq): string
    {
        $shopName = $profile->name;
        $whatsapp = $profile->whatsapp;

        $prompt = <<<PROMPT
You are a helpful WhatsApp assistant for "{$shopName}", an online shop in Bangladesh.

IMPORTANT RULES:
1. ALWAYS respond in Bengali (বাংলা) unless the customer writes in English
2. Be friendly, helpful, and professional
3. Keep responses concise and mobile-friendly
4. Use emojis sparingly but appropriately
5. If you don't know something, ask the customer to contact the seller directly

SHOP INFORMATION:
- Shop Name: {$shopName}
- WhatsApp: {$whatsapp}

PROMPT;

        // Add product info if matched
        if ($product) {
            $price = number_format($product->price, 0);
            $prompt .= <<<PRODUCT

PRODUCT FOUND:
- Name: {$product->name}
- Price: ৳{$price}
- Description: {$product->description}
- Status: {$product->status}

If the customer is asking about this product, provide the information.
PRODUCT;
        }

        // Add FAQ info if matched
        if ($faq) {
            $prompt .= <<<FAQ

FAQ MATCH FOUND:
Q: {$faq['question']}
A: {$faq['answer']}

Use this information to answer the customer's question.
FAQ;
        }

        // Add custom AI instructions if available
        if ($settings && $settings->ai_instructions) {
            $prompt .= "\n\nADDITIONAL INSTRUCTIONS:\n{$settings->ai_instructions}";
        }

        // Add available products summary
        $products = $profile->products()->where('status', 'available')->take(5)->get();
        if ($products->count() > 0) {
            $prompt .= "\n\nAVAILABLE PRODUCTS:";
            foreach ($products as $p) {
                $price = number_format($p->price, 0);
                $prompt .= "\n- {$p->name}: ৳{$price}";
            }
        }

        // Add payment methods
        $paymentMethods = $profile->paymentMethods()->where('is_active', true)->get();
        if ($paymentMethods->count() > 0) {
            $prompt .= "\n\nPAYMENT METHODS:";
            foreach ($paymentMethods as $pm) {
                $prompt .= "\n- {$pm->type}: {$pm->account_number}";
                if ($pm->account_name) {
                    $prompt .= " ({$pm->account_name})";
                }
            }
        }

        return $prompt;
    }

    /**
     * Get conversation history for context
     */
    protected function getConversationHistory(WhatsappConversation $conversation): array
    {
        $messages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        $history = [];
        foreach ($messages as $msg) {
            $role = $msg->direction === 'incoming' ? 'user' : 'assistant';
            $history[] = [
                'role' => $role,
                'content' => $msg->content,
            ];
        }

        return $history;
    }

    /**
     * Call AI API
     */
    protected function callAI(string $systemPrompt, array $history, string $message): ?string
    {
        if (!$this->apiKey) {
            Log::warning('AI API key not configured');
            return null;
        }

        try {
            // Add current message to history
            $messages = $history;
            $messages[] = ['role' => 'user', 'content' => $message];

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout(30)
            ->post($this->apiUrl, [
                'model' => $this->model,
                'max_tokens' => 500,
                'system' => $systemPrompt,
                'messages' => $messages,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'][0]['text'] ?? null;
            }

            Log::error('AI API Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('AI API Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get fallback response when AI is unavailable
     */
    protected function getFallbackResponse(bool $isOrderIntent, bool $isPaymentQuery, ?Product $product): string
    {
        if ($isOrderIntent) {
            return "আপনার অর্ডারের জন্য ধন্যবাদ! 🙏\n\nঅর্ডার কনফার্ম করতে আপনার নাম, ঠিকানা এবং মোবাইল নম্বর পাঠান।\n\nশীঘ্রই আমরা আপনার সাথে যোগাযোগ করব।";
        }

        if ($isPaymentQuery) {
            return "পেমেন্ট সংক্রান্ত তথ্যের জন্য \"পেমেন্ট\" লিখে পাঠান।\n\nঅথবা সরাসরি সেলারের সাথে যোগাযোগ করুন।";
        }

        if ($product) {
            $price = number_format($product->price, 0);
            return "🛍️ *{$product->name}*\n\n💰 মূল্য: ৳{$price}\n\nঅর্ডার করতে \"অর্ডার\" লিখে পাঠান।";
        }

        return "আপনার মেসেজের জন্য ধন্যবাদ! 🙏\n\nশীঘ্রই আমরা আপনার সাথে যোগাযোগ করব।\n\nপ্রোডাক্ট দেখতে \"প্রোডাক্ট\" লিখুন।";
    }

    /**
     * Generate order confirmation message
     */
    public function generateOrderMessage(array $orderDetails, $profile): string
    {
        $items = $orderDetails['items'] ?? [];
        $total = $orderDetails['total'] ?? 0;
        $customerName = $orderDetails['customer_name'] ?? 'গ্রাহক';
        $address = $orderDetails['address'] ?? '';
        $phone = $orderDetails['phone'] ?? '';

        $message = "📦 *নতুন অর্ডার*\n\n";
        $message .= "👤 নাম: {$customerName}\n";
        $message .= "📱 ফোন: {$phone}\n";
        $message .= "📍 ঠিকানা: {$address}\n\n";

        $message .= "🛒 *অর্ডার ডিটেইলস:*\n";
        foreach ($items as $item) {
            $message .= "• {$item['name']} x {$item['quantity']} = ৳{$item['subtotal']}\n";
        }

        $message .= "\n💰 *মোট:* ৳" . number_format($total, 0) . "\n\n";
        $message .= "ধন্যবাদ আপনার অর্ডারের জন্য! 🙏";

        return $message;
    }
}
