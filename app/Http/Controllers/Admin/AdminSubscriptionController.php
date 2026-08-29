<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', '');
        $planId = $request->input('plan_id', '');

        $freePlan = Plan::where('slug', 'free')->orWhere('price', 0)->first();

        // List every user with their current plan/subscription so that
        // free-plan users (who have no subscription row) also appear here.
        $query = User::query()->with(['subscriptions.plan']);

        // Status filter
        if (in_array($status, ['active', 'pending', 'cancelled'], true)) {
            $query->whereHas('subscriptions', fn ($q) => $q->where('status', $status));
        } elseif ($status === 'free') {
            $query->whereDoesntHave('subscriptions', fn ($q) => $q->whereIn('status', ['active', 'pending']));
        }

        // Plan filter
        if ($planId !== '' && $planId !== null) {
            if ($freePlan && (int) $planId === $freePlan->id) {
                $query->whereDoesntHave('subscriptions', fn ($q) => $q->whereIn('status', ['active', 'pending']));
            } else {
                $query->whereHas('subscriptions', fn ($q) => $q->where('plan_id', $planId)->whereIn('status', ['active', 'pending']));
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Transform each user into a subscription-style row
        $users->getCollection()->transform(function (User $user) use ($status, $freePlan) {
            // Pick the subscription that best represents the row
            if (in_array($status, ['active', 'pending', 'cancelled'], true)) {
                $sub = $user->subscriptions->firstWhere('status', $status);
            } else {
                $sub = $user->subscriptions->firstWhere('status', 'active')
                    ?? $user->subscriptions->firstWhere('status', 'pending');
            }

            $hasSub = $sub !== null;
            $plan = $hasSub && $sub->plan ? $sub->plan : $freePlan;

            return [
                // Real subscription id when actionable, otherwise a unique
                // negative id (per user) so front-end keys never collide.
                'id' => $hasSub ? $sub->id : -$user->id,
                'status' => $hasSub ? $sub->status : 'free',
                'starts_at' => $hasSub ? $sub->starts_at : null,
                'ends_at' => $hasSub ? $sub->ends_at : null,
                'cancelled_at' => $hasSub ? $sub->cancelled_at : null,
                'created_at' => $user->created_at,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'plan' => [
                    'id' => $plan?->id ?? 0,
                    'name' => $plan?->name ?? 'Free',
                    'price' => (float) ($plan?->price ?? 0),
                ],
            ];
        });

        $plans = Plan::where('is_active', true)->get(['id', 'name', 'price']);

        return Inertia::render('Admin/Subscriptions/Index', [
            'subscriptions' => $users,
            'plans' => $plans,
            'filters' => [
                'status' => (string) $status,
                'plan_id' => (string) $planId,
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
