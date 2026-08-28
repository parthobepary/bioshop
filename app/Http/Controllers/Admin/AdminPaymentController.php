<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['user', 'subscription.plan'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Payment::sum('amount'),
            'completed' => Payment::where('status', 'completed')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'pending_count' => Payment::where('status', 'pending')->count(),
        ];

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'stats' => $stats,
            'filters' => [
                'status' => '',
                'method' => '',
                'from' => '',
                'to' => '',
            ],
        ]);
    }

    public function approve(Payment $payment)
    {
        $payment->update(['status' => 'completed']);
        if ($payment->subscription) {
            $payment->subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);
        }
        return back()->with('success', 'Payment approved.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $payment->update(['status' => 'failed']);
        if ($payment->subscription) {
            $payment->subscription->update(['status' => 'cancelled']);
        }
        return back()->with('success', 'Payment rejected.');
    }

    public function refund(Payment $payment)
    {
        $payment->update(['status' => 'refunded']);
        if ($payment->subscription && $payment->subscription->status === 'active') {
            $payment->subscription->update(['status' => 'cancelled']);
        }
        return back()->with('success', 'Payment refunded.');
    }
}
