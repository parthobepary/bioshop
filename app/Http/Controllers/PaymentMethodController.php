<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;
        $paymentMethods = $profile->paymentMethods()
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Dashboard/Payment/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'type' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'bank', 'other'])],
            'account_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'qr_code' => ['nullable', 'image', 'max:1024'], // 1MB max
            'instructions' => ['nullable', 'string', 'max:500'],
        ]);

        // Handle QR code upload
        $qrCodePath = null;
        if ($request->hasFile('qr_code')) {
            $qrCodePath = $request->file('qr_code')->store('qr-codes', 'public');
        }

        // Get the next sort order
        $maxSortOrder = $profile->paymentMethods()->max('sort_order') ?? 0;

        $paymentMethod = $profile->paymentMethods()->create([
            'type' => $validated['type'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'qr_code' => $qrCodePath,
            'instructions' => $validated['instructions'] ?? null,
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Payment method added successfully!');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $paymentMethod);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'bank', 'other'])],
            'account_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'qr_code' => ['nullable', 'image', 'max:1024'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'remove_qr' => ['nullable', 'boolean'],
        ]);

        // Handle QR code
        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            if ($paymentMethod->qr_code) {
                Storage::disk('public')->delete($paymentMethod->qr_code);
            }
            $validated['qr_code'] = $request->file('qr_code')->store('qr-codes', 'public');
        } elseif ($request->boolean('remove_qr') && $paymentMethod->qr_code) {
            Storage::disk('public')->delete($paymentMethod->qr_code);
            $validated['qr_code'] = null;
        } else {
            unset($validated['qr_code']);
        }

        unset($validated['remove_qr']);

        $paymentMethod->update($validated);

        return back()->with('success', 'Payment method updated successfully!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->authorize('delete', $paymentMethod);

        // Delete QR code if exists
        if ($paymentMethod->qr_code) {
            Storage::disk('public')->delete($paymentMethod->qr_code);
        }

        $paymentMethod->delete();

        return back()->with('success', 'Payment method deleted successfully!');
    }

    public function toggle(PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $paymentMethod);

        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        return back()->with('success', $paymentMethod->is_active ? 'Payment method enabled!' : 'Payment method disabled!');
    }

    public function reorder(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'methods' => ['required', 'array'],
            'methods.*.id' => ['required', 'integer', 'exists:payment_methods,id'],
            'methods.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['methods'] as $methodData) {
            $method = PaymentMethod::find($methodData['id']);

            if ($method && $method->profile_id === $profile->id) {
                $method->update(['sort_order' => $methodData['sort_order']]);
            }
        }

        return back()->with('success', 'Payment methods reordered!');
    }
}
