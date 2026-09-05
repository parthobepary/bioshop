<?php

namespace App\Http\Controllers;

use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Webhook verification (GET)
     */
    public function verifyWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $result = $this->whatsappService->verifyWebhook($mode, $token, $challenge);

        if ($result) {
            return response($result, 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Webhook handler (POST)
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        // Process webhook asynchronously
        dispatch(function () use ($data) {
            $this->whatsappService->processWebhook($data);
        })->afterResponse();

        return response('OK', 200);
    }

    /**
     * Display conversations list
     */
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        $query = WhatsappConversation::where('profile_id', $profile->id)
            ->with('latestMessage')
            ->withCount('messages');

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $conversations = $query->orderBy('last_message_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Stats
        $stats = [
            'total' => WhatsappConversation::where('profile_id', $profile->id)->count(),
            'active' => WhatsappConversation::where('profile_id', $profile->id)->where('status', 'active')->count(),
            'unread' => WhatsappConversation::where('profile_id', $profile->id)->where('unread_count', '>', 0)->count(),
        ];

        return Inertia::render('Dashboard/WhatsApp/Index', [
            'conversations' => $conversations,
            'stats' => $stats,
            'apiConfigured' => $this->whatsappService->isConfigured(),
            'filters' => [
                'status' => $request->input('status', ''),
                'search' => $request->input('search', ''),
            ],
        ]);
    }

    /**
     * View single conversation
     */
    public function show(Request $request, WhatsappConversation $conversation)
    {
        $profile = $request->user()->profile;

        // Verify ownership
        if ($conversation->profile_id !== $profile->id) {
            abort(403);
        }

        // Mark as read
        $conversation->markAsRead();

        // Load messages
        $messages = $conversation->messages()
            ->with('product')
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Dashboard/WhatsApp/Conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Send reply to conversation
     */
    public function reply(Request $request, WhatsappConversation $conversation)
    {
        $profile = $request->user()->profile;

        // Verify ownership
        if ($conversation->profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4096'],
        ]);

        // Send message
        $result = $this->whatsappService->sendSellerMessage($conversation, $validated['message']);

        if (!$result) {
            return back()->withErrors(['message' => 'Failed to send message. Please try again.']);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Update conversation status
     */
    public function updateStatus(Request $request, WhatsappConversation $conversation)
    {
        $profile = $request->user()->profile;

        if ($conversation->profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:active,resolved,pending'],
        ]);

        $conversation->update(['status' => $validated['status']]);

        return back()->with('success', 'Conversation status updated.');
    }

    /**
     * Display WhatsApp settings
     */
    public function settings(Request $request)
    {
        $profile = $request->user()->profile;

        $settings = WhatsappSetting::firstOrCreate(
            ['profile_id' => $profile->id],
            [
                'ai_enabled' => true,
                'auto_reply_enabled' => true,
                'order_notifications' => true,
                'faq_items' => (new WhatsappSetting())->getDefaultFaqItems(),
                'business_days' => ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'],
            ]
        );

        return Inertia::render('Dashboard/WhatsApp/Settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update WhatsApp settings
     */
    public function updateSettings(Request $request)
    {
        $profile = $request->user()->profile;

        $validated = $request->validate([
            'ai_enabled' => ['boolean'],
            'auto_reply_enabled' => ['boolean'],
            'order_notifications' => ['boolean'],
            'welcome_message' => ['nullable', 'string', 'max:500'],
            'away_message' => ['nullable', 'string', 'max:500'],
            'business_hours_start' => ['nullable', 'string'],
            'business_hours_end' => ['nullable', 'string'],
            'business_days' => ['nullable', 'array'],
            'ai_instructions' => ['nullable', 'string', 'max:1000'],
            'faq_items' => ['nullable', 'array'],
            'faq_items.*.question' => ['required', 'string', 'max:255'],
            'faq_items.*.answer' => ['required', 'string', 'max:500'],
            'quick_replies' => ['nullable', 'array'],
            'quick_replies.*' => ['string', 'max:100'],
        ]);

        $settings = WhatsappSetting::updateOrCreate(
            ['profile_id' => $profile->id],
            $validated
        );

        return back()->with('success', 'WhatsApp settings updated successfully.');
    }

    /**
     * Get conversations for API (real-time updates)
     */
    public function apiConversations(Request $request)
    {
        $profile = $request->user()->profile;

        $conversations = WhatsappConversation::where('profile_id', $profile->id)
            ->with('latestMessage')
            ->where('unread_count', '>', 0)
            ->orderBy('last_message_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'conversations' => $conversations,
            'unread_count' => $conversations->sum('unread_count'),
        ]);
    }

    /**
     * Get messages for a conversation (API)
     */
    public function apiMessages(Request $request, WhatsappConversation $conversation)
    {
        $profile = $request->user()->profile;

        if ($conversation->profile_id !== $profile->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json(['messages' => $messages]);
    }
}
