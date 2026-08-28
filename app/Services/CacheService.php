<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache TTL in seconds (1 hour)
     */
    protected const TTL = 3600;

    /**
     * Get public profile data with caching
     */
    public function getPublicProfile(string $username): ?array
    {
        return Cache::remember(
            "profile:{$username}",
            self::TTL,
            function () use ($username) {
                $profile = Profile::where('username', $username)
                    ->where('is_active', true)
                    ->with([
                        'links' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
                        'categories' => fn($q) => $q->orderBy('sort_order'),
                        'products' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->with('images'),
                        'paymentMethods' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
                    ])
                    ->first();

                return $profile?->toArray();
            }
        );
    }

    /**
     * Clear profile cache
     */
    public function clearProfileCache(string $username): void
    {
        Cache::forget("profile:{$username}");
    }

    /**
     * Clear all caches for a profile
     */
    public function clearAllProfileCaches(Profile $profile): void
    {
        $this->clearProfileCache($profile->username);
        Cache::forget("analytics:{$profile->id}");
        Cache::forget("products:{$profile->id}");
    }

    /**
     * Get analytics summary with caching
     */
    public function getAnalyticsSummary(int $profileId, string $period = '7d'): array
    {
        return Cache::remember(
            "analytics:{$profileId}:{$period}",
            300, // 5 minutes
            function () use ($profileId, $period) {
                $profile = Profile::find($profileId);
                if (!$profile) {
                    return [];
                }

                $days = match ($period) {
                    '24h' => 1,
                    '7d' => 7,
                    '30d' => 30,
                    '90d' => 90,
                    default => 7,
                };

                $startDate = now()->subDays($days);

                return [
                    'page_views' => $profile->pageViews()->where('created_at', '>=', $startDate)->count(),
                    'link_clicks' => $profile->links()->withCount(['clicks' => fn($q) => $q->where('created_at', '>=', $startDate)])->get()->sum('clicks_count'),
                    'product_views' => $profile->products()->withCount(['views' => fn($q) => $q->where('created_at', '>=', $startDate)])->get()->sum('views_count'),
                    'whatsapp_clicks' => $profile->whatsappClicks()->where('created_at', '>=', $startDate)->count(),
                ];
            }
        );
    }

    /**
     * Get plans with caching
     */
    public function getPlans(): array
    {
        return Cache::remember('plans', 86400, function () {
            return \App\Models\Plan::where('is_active', true)
                ->orderBy('price')
                ->get()
                ->toArray();
        });
    }

    /**
     * Clear plans cache
     */
    public function clearPlansCache(): void
    {
        Cache::forget('plans');
    }
}
