<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan']);

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by plan
        if ($planId = $request->input('plan_id')) {
            $query->where('plan_id', $planId);
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        // Get plans for filter
        $plans = \App\Models\Plan::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/Subscriptions/Index', [
            'subscriptions' => $subscriptions,
            'plans' => $plans,
            'filters' => [
                'status' => $request->input('status', ''),
                'plan_id' => $request->input('plan_id', ''),
            ],
        ]);
    }

    public function activate(Subscription $subscription)
    {
        if ($subscription->status === 'active') {
            return back()->withErrors(['subscription' => 'Subscription is already active.']);
        }

        // Cancel any existing active subscription for this user
        Subscription::where('user_id', $subscription->user_id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Activate this subscription
        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Update the related payment
        Payment::where('subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed']);

        return back()->with('success', 'Subscription has been activated successfully.');
    }

    public function cancel(Subscription $subscription)
    {
        if ($subscription->status !== 'active') {
            return back()->withErrors(['subscription' => 'Subscription is not active.']);
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Subscription has been cancelled.');
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'months' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        if ($subscription->status !== 'active') {
            return back()->withErrors(['subscription' => 'Can only extend active subscriptions.']);
        }

        $currentEnd = $subscription->ends_at ?? now();
        $newEnd = Carbon::parse($currentEnd)->addMonths($validated['months']);

        $subscription->update([
            'ends_at' => $newEnd,
        ]);

        return back()->with('success', "Subscription extended by {$validated['months']} month(s).");
    }
}
