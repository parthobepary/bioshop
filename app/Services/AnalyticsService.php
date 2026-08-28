<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\PageView;
use App\Models\LinkClick;
use App\Models\ProductView;
use App\Models\WhatsappClick;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function __construct(
        protected Profile $profile
    ) {}

    /**
     * Get overview stats for the profile
     */
    public function getOverviewStats(): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'page_views' => [
                'total' => $this->getTotalPageViews(),
                'today' => $this->getPageViewsCount($today),
                'this_week' => $this->getPageViewsCount($thisWeek),
                'this_month' => $this->getPageViewsCount($thisMonth),
            ],
            'link_clicks' => [
                'total' => $this->getTotalLinkClicks(),
                'today' => $this->getLinkClicksCount($today),
                'this_week' => $this->getLinkClicksCount($thisWeek),
                'this_month' => $this->getLinkClicksCount($thisMonth),
            ],
            'product_views' => [
                'total' => $this->getTotalProductViews(),
                'today' => $this->getProductViewsCount($today),
                'this_week' => $this->getProductViewsCount($thisWeek),
                'this_month' => $this->getProductViewsCount($thisMonth),
            ],
            'whatsapp_clicks' => [
                'total' => $this->getTotalWhatsappClicks(),
                'today' => $this->getWhatsappClicksCount($today),
                'this_week' => $this->getWhatsappClicksCount($thisWeek),
                'this_month' => $this->getWhatsappClicksCount($thisMonth),
            ],
        ];
    }

    /**
     * Get page views chart data for the last N days
     */
    public function getPageViewsChart(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $views = PageView::where('profile_id', $this->profile->id)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = $views[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get top products by views
     */
    public function getTopProducts(int $limit = 5): array
    {
        $productIds = $this->profile->products()->pluck('id');

        return ProductView::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('COUNT(*) as views'))
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->limit($limit)
            ->with('product:id,name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product?->name ?? 'Deleted Product',
                    'views' => $item->views,
                ];
            })
            ->toArray();
    }

    /**
     * Get top links by clicks
     */
    public function getTopLinks(int $limit = 5): array
    {
        $linkIds = $this->profile->links()->pluck('id');

        return LinkClick::whereIn('link_id', $linkIds)
            ->select('link_id', DB::raw('COUNT(*) as clicks'))
            ->groupBy('link_id')
            ->orderByDesc('clicks')
            ->limit($limit)
            ->with('link:id,title')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->link_id,
                    'title' => $item->link?->title ?? 'Deleted Link',
                    'clicks' => $item->clicks,
                ];
            })
            ->toArray();
    }

    /**
     * Get traffic sources (referrers)
     */
    public function getTrafficSources(int $limit = 5): array
    {
        return PageView::where('profile_id', $this->profile->id)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->select('referrer', DB::raw('COUNT(*) as count'))
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $host = parse_url($item->referrer, PHP_URL_HOST) ?? $item->referrer;
                return [
                    'source' => $host,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(int $limit = 10): array
    {
        $productIds = $this->profile->products()->pluck('id');
        $linkIds = $this->profile->links()->pluck('id');

        $pageViews = PageView::where('profile_id', $this->profile->id)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($v) => [
                'type' => 'page_view',
                'description' => 'Someone viewed your page',
                'created_at' => $v->created_at,
            ]);

        $productViews = ProductView::whereIn('product_id', $productIds)
            ->with('product:id,name')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($v) => [
                'type' => 'product_view',
                'description' => "Someone viewed \"{$v->product?->name}\"",
                'created_at' => $v->created_at,
            ]);

        $linkClicks = LinkClick::whereIn('link_id', $linkIds)
            ->with('link:id,title')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($v) => [
                'type' => 'link_click',
                'description' => "Someone clicked \"{$v->link?->title}\"",
                'created_at' => $v->created_at,
            ]);

        $whatsappClicks = WhatsappClick::where('profile_id', $this->profile->id)
            ->with('product:id,name')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($v) => [
                'type' => 'whatsapp_click',
                'description' => $v->product
                    ? "Someone inquired about \"{$v->product->name}\" on WhatsApp"
                    : 'Someone contacted you on WhatsApp',
                'created_at' => $v->created_at,
            ]);

        return collect()
            ->merge($pageViews)
            ->merge($productViews)
            ->merge($linkClicks)
            ->merge($whatsappClicks)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    // Private helper methods

    private function getTotalPageViews(): int
    {
        return PageView::where('profile_id', $this->profile->id)->count();
    }

    private function getPageViewsCount(Carbon $since): int
    {
        return PageView::where('profile_id', $this->profile->id)
            ->where('created_at', '>=', $since)
            ->count();
    }

    private function getTotalLinkClicks(): int
    {
        $linkIds = $this->profile->links()->pluck('id');
        return LinkClick::whereIn('link_id', $linkIds)->count();
    }

    private function getLinkClicksCount(Carbon $since): int
    {
        $linkIds = $this->profile->links()->pluck('id');
        return LinkClick::whereIn('link_id', $linkIds)
            ->where('created_at', '>=', $since)
            ->count();
    }

    private function getTotalProductViews(): int
    {
        $productIds = $this->profile->products()->pluck('id');
        return ProductView::whereIn('product_id', $productIds)->count();
    }

    private function getProductViewsCount(Carbon $since): int
    {
        $productIds = $this->profile->products()->pluck('id');
        return ProductView::whereIn('product_id', $productIds)
            ->where('created_at', '>=', $since)
            ->count();
    }

    private function getTotalWhatsappClicks(): int
    {
        return WhatsappClick::where('profile_id', $this->profile->id)->count();
    }

    private function getWhatsappClicksCount(Carbon $since): int
    {
        return WhatsappClick::where('profile_id', $this->profile->id)
            ->where('created_at', '>=', $since)
            ->count();
    }
}
