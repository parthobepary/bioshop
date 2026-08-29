<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Support\Media;
use App\Models\PageView;
use App\Models\LinkClick;
use App\Models\ProductView;
use App\Models\WhatsappClick;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicPageController extends Controller
{
    public function show(string $username)
    {
        $profile = Profile::where('username', $username)
            ->with([
                'links' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                },
                'categories' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'products' => function ($query) {
                    $query->where('is_active', true)
                        ->with(['images', 'category'])
                        ->orderBy('sort_order');
                },
                'paymentMethods' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                },
            ])
            ->firstOrFail();

        // Record page view
        $this->recordPageView($profile);

        return Inertia::render('Public/Shop', [
            'profile' => $profile,
            'links' => $profile->links,
            'categories' => $profile->categories,
            'products' => $profile->products,
            'paymentMethods' => $profile->paymentMethods,
            'seo' => [
                'title' => $profile->seo_title ?: $profile->name,
                'description' => $profile->seo_description ?: $profile->bio,
                'image' => Media::url($profile->photo),
                'url' => url($profile->username),
            ],
        ]);
    }

    public function trackLinkClick(Request $request, int $linkId)
    {
        LinkClick::create([
            'link_id' => $linkId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);

        return response()->json(['success' => true]);
    }

    public function trackProductView(Request $request, int $productId)
    {
        ProductView::create([
            'product_id' => $productId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }

    public function trackWhatsappClick(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => ['required', 'exists:profiles,id'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        WhatsappClick::create([
            'profile_id' => $validated['profile_id'],
            'product_id' => $validated['product_id'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }

    private function recordPageView(Profile $profile): void
    {
        $request = request();

        PageView::create([
            'profile_id' => $profile->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);
    }
}
