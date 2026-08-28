<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $currentPlan = $user->currentPlan();

        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->first();

        $payments = $user->payments()
            ->with('subscription.plan')
            ->latest()
            ->take(10)
            ->get();

        $pendingPayment = $user->payments()
            ->where('status', 'pending')
            ->with('subscription.plan')
            ->first();

        return Inertia::render('Dashboard/Billing/Index', [
            'currentPlan' => $currentPlan,
            'subscription' => $subscription,
            'payments' => $payments,
            'pendingPayment' => $pendingPayment,
            'usage' => [
                'products' => [
                    'used' => $user->profile?->products()->count() ?? 0,
                    'limit' => $currentPlan?->max_products ?? 5,
                ],
                'links' => [
                    'used' => $user->profile?->links()->count() ?? 0,
                    'limit' => $currentPlan?->max_links ?? 5,
                ],
            ],
        ]);
    }

    public function upgrade(Request $request)
    {
        $user = $request->user();
        $currentPlan = $user->currentPlan();

        $plans = Plan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return Inertia::render('Dashboard/Billing/Upgrade', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
        ]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'payment_method' => ['required', 'in:bkash,nagad,rocket,bank'],
            'transaction_id' => ['required', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user = $request->user();
        $plan = Plan::findOrFail($validated['plan_id']);

        // Check if user already has a pending payment
        $existingPending = $user->payments()->where('status', 'pending')->first();
        if ($existingPending) {
            return back()->withErrors([
                'payment' => 'You already have a pending payment. Please wait for it to be verified.',
            ]);
        }

        // Create pending subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'starts_at' => null,
            'ends_at' => null,
        ]);

        // Create pending payment
        Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'status' => 'pending',
            'notes' => $validated['phone_number'] ? "Phone: {$validated['phone_number']}" : null,
        ]);

        return redirect()->route('billing.index')->with('success',
            'Payment submitted successfully! Your subscription will be activated once we verify your payment. This usually takes 1-24 hours.'
        );
    }

    public function cancelSubscription(Request $request)
    {
        $user = $request->user();

        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return back()->withErrors([
                'subscription' => 'No active subscription found.',
            ]);
        }

        // Don't actually cancel, just mark as cancelled at end of period
        $subscription->update([
            'cancelled_at' => now(),
        ]);

        return back()->with('success',
            'Your subscription has been cancelled. You will continue to have access until the end of your billing period.'
        );
    }

    public function paymentHistory(Request $request)
    {
        $user = $request->user();

        $payments = $user->payments()
            ->with('subscription.plan')
            ->latest()
            ->paginate(20);

        return Inertia::render('Dashboard/Billing/History', [
            'payments' => $payments,
        ]);
    }
}
