<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function home()
    {
        $stats = [
            'users' => User::count(),
            'profiles' => Profile::count(),
            'products' => \App\Models\Product::count(),
        ];

        $plans = Plan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return Inertia::render('Landing/Home', [
            'stats' => $stats,
            'plans' => $plans,
        ]);
    }

    public function pricing()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return Inertia::render('Landing/Pricing', [
            'plans' => $plans,
        ]);
    }

    public function features()
    {
        return Inertia::render('Landing/Features');
    }

    public function about()
    {
        return Inertia::render('Landing/About');
    }

    public function contact()
    {
        return Inertia::render('Landing/Contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // TODO: Send email or store in database
        // For now, just return success

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    public function terms()
    {
        return Inertia::render('Landing/Terms');
    }

    public function privacy()
    {
        return Inertia::render('Landing/Privacy');
    }
}
