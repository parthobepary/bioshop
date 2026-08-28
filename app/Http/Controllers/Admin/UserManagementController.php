<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['profile', 'subscriptions.plan'])
            ->withCount(['payments as completed_payments_count' => function ($q) {
                $q->where('status', 'completed');
            }]);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'banned') {
                $query->where('is_active', false);
            }
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $request->input('search', ''),
                'status' => $request->input('status', ''),
                'role' => $request->input('role', ''),
            ],
        ]);
    }

    public function show(User $user)
    {
        $user->load([
            'profile.products',
            'profile.links',
            'profile.paymentMethods',
            'subscriptions.plan',
            'payments.subscription.plan',
        ]);

        // Get analytics if profile exists
        $analytics = null;
        if ($user->profile) {
            $analyticsService = new AnalyticsService($user->profile);
            $analytics = $analyticsService->getOverviewStats();
        }

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'analytics' => $analytics,
        ]);
    }

    public function toggleBan(User $user)
    {
        // Don't allow banning yourself or other admins
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot ban yourself.']);
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'You cannot ban another admin.']);
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $action = $user->is_active ? 'unbanned' : 'banned';

        return back()->with('success', "User has been {$action} successfully.");
    }

    public function makeAdmin(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'User is already an admin.']);
        }

        $user->update([
            'role' => 'admin',
        ]);

        return back()->with('success', 'User has been promoted to admin.');
    }

    public function removeAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot remove your own admin status.']);
        }

        if (!$user->isAdmin()) {
            return back()->withErrors(['user' => 'User is not an admin.']);
        }

        $user->update([
            'role' => 'user',
        ]);

        return back()->with('success', 'Admin status has been removed.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete yourself.']);
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'You cannot delete another admin.']);
        }

        // Delete associated data
        if ($user->profile) {
            $user->profile->products()->delete();
            $user->profile->links()->delete();
            $user->profile->paymentMethods()->delete();
            $user->profile->delete();
        }

        $user->subscriptions()->delete();
        $user->payments()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User has been deleted.');
    }
}
