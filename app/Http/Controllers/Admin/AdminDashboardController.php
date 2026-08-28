<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Platform stats
        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', $today)->count(),
            'new_users_this_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'total_profiles' => Profile::count(),
            'total_products' => Product::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'revenue_this_month' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_page_views' => PageView::count(),
            'page_views_today' => PageView::whereDate('created_at', $today)->count(),
        ];

        // Recent users
        $recentUsers = User::with('profile')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->profile?->username,
                'created_at' => $user->created_at,
            ]);

        // Pending payments
        $pendingPayments = Payment::with(['user', 'subscription.plan'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // User growth chart (last 30 days)
        $userGrowth = $this->getUserGrowthData(30);

        // Revenue chart (last 30 days)
        $revenueChart = $this->getRevenueData(30);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'pendingPayments' => $pendingPayments,
            'userGrowth' => $userGrowth,
            'revenueChart' => $revenueChart,
        ]);
    }

    private function getUserGrowthData(int $days): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $users = User::where('created_at', '>=', $startDate)
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
            $data[] = $users[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getRevenueData(int $days): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $revenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = (float) ($revenue[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
