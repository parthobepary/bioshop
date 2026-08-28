<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LinkController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;
        $links = $profile->links()->orderBy('sort_order')->get();

        return Inertia::render('Dashboard/Links/Index', [
            'links' => $links,
        ]);
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        // Check plan limits
        $plan = Auth::user()->activeSubscription?->plan;
        $maxLinks = $plan?->max_links ?? 5; // Default to free plan limit

        if ($maxLinks !== -1 && $profile->links()->count() >= $maxLinks) {
            return back()->with('error', "You've reached your plan's limit of {$maxLinks} links. Upgrade to add more.");
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        // Auto-detect icon from URL if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = $this->detectIconFromUrl($validated['url']);
        }

        // Get the next sort order
        $maxSortOrder = $profile->links()->max('sort_order') ?? 0;

        $link = $profile->links()->create([
            ...$validated,
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Link added successfully!');
    }

    public function update(Request $request, Link $link)
    {
        // Ensure the link belongs to the user's profile
        $this->authorize('update', $link);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        // Auto-detect icon from URL if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = $this->detectIconFromUrl($validated['url']);
        }

        $link->update($validated);

        return back()->with('success', 'Link updated successfully!');
    }

    public function destroy(Link $link)
    {
        // Ensure the link belongs to the user's profile
        $this->authorize('delete', $link);

        $link->delete();

        return back()->with('success', 'Link deleted successfully!');
    }

    public function toggle(Link $link)
    {
        // Ensure the link belongs to the user's profile
        $this->authorize('update', $link);

        $link->update(['is_active' => !$link->is_active]);

        return back()->with('success', $link->is_active ? 'Link enabled!' : 'Link disabled!');
    }

    public function reorder(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'links' => ['required', 'array'],
            'links.*.id' => ['required', 'integer', 'exists:links,id'],
            'links.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['links'] as $linkData) {
            $link = Link::find($linkData['id']);

            // Verify ownership
            if ($link && $link->profile_id === $profile->id) {
                $link->update(['sort_order' => $linkData['sort_order']]);
            }
        }

        return back()->with('success', 'Links reordered!');
    }

    /**
     * Detect icon name from URL domain
     */
    private function detectIconFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $host = str_replace('www.', '', $host ?? '');

        $iconMap = [
            'facebook.com' => 'facebook',
            'fb.com' => 'facebook',
            'instagram.com' => 'instagram',
            'twitter.com' => 'twitter',
            'x.com' => 'twitter',
            'youtube.com' => 'youtube',
            'youtu.be' => 'youtube',
            'tiktok.com' => 'tiktok',
            'linkedin.com' => 'linkedin',
            'github.com' => 'github',
            'pinterest.com' => 'pinterest',
            'snapchat.com' => 'snapchat',
            'telegram.org' => 'telegram',
            't.me' => 'telegram',
            'whatsapp.com' => 'whatsapp',
            'wa.me' => 'whatsapp',
            'discord.com' => 'discord',
            'discord.gg' => 'discord',
            'twitch.tv' => 'twitch',
            'spotify.com' => 'spotify',
            'soundcloud.com' => 'soundcloud',
            'medium.com' => 'medium',
            'reddit.com' => 'reddit',
            'tumblr.com' => 'tumblr',
            'dribbble.com' => 'dribbble',
            'behance.net' => 'behance',
            'vimeo.com' => 'vimeo',
            'flickr.com' => 'flickr',
        ];

        foreach ($iconMap as $domain => $icon) {
            if (str_contains($host, $domain)) {
                return $icon;
            }
        }

        return 'link'; // Default icon
    }
}
