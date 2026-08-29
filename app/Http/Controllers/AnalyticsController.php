<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->profile;
        $analytics = new AnalyticsService($profile);

        $days = (int) $request->input('days', 30);
        $days = in_array($days, [7, 14, 30, 90], true) ? $days : 30;

        return Inertia::render('Dashboard/Analytics/Index', [
            'stats' => $analytics->getOverviewStats(),
            'chartData' => $analytics->getPageViewsChart($days),
            'topProducts' => $analytics->getTopProducts(5),
            'topLinks' => $analytics->getTopLinks(5),
            'trafficSources' => $analytics->getTrafficSources(5),
            'recentActivity' => $analytics->getRecentActivity(10),
            'selectedDays' => $days,
        ]);
    }

    public function dashboardStats(Request $request)
    {
        $profile = $request->user()->profile;
        $analytics = new AnalyticsService($profile);

        return [
            'stats' => $analytics->getOverviewStats(),
            'chartData' => $analytics->getPageViewsChart(7),
            'topProducts' => $analytics->getTopProducts(3),
            'recentActivity' => $analytics->getRecentActivity(5),
        ];
    }
}
