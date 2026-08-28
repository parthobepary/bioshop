<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('setup.index');
        }

        $analytics = new AnalyticsService($profile);

        return Inertia::render('Dashboard', [
            'profile' => $profile,
            'stats' => $analytics->getOverviewStats(),
            'chartData' => $analytics->getPageViewsChart(7),
            'topProducts' => $analytics->getTopProducts(3),
            'recentActivity' => $analytics->getRecentActivity(5),
        ]);
    }
}
