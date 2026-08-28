<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileSetupController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If user already has a profile, redirect to dashboard
        if ($user->hasProfile()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('ProfileSetup/Index', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // If user already has a profile, redirect to dashboard
        if ($user->hasProfile()) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('profiles', 'username'),
            ],
            'name' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'whatsapp' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'theme_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ], [
            'username.regex' => 'Username can only contain lowercase letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'whatsapp.regex' => 'Please enter a valid Bangladesh phone number (e.g., 01712345678).',
            'theme_color.regex' => 'Please select a valid color.',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profiles', 'public');
        }

        // Create the profile
        $profile = Profile::create([
            'user_id' => $user->id,
            'username' => $validated['username'],
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'whatsapp' => $validated['whatsapp'],
            'theme_color' => $validated['theme_color'],
            'photo' => $photoPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
    }

    public function checkUsername(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30'],
        ]);

        $username = Str::lower($request->username);
        $exists = Profile::where('username', $username)->exists();

        // Check for reserved usernames
        $reserved = ['admin', 'api', 'www', 'mail', 'ftp', 'login', 'register', 'dashboard', 'settings', 'billing', 'help', 'support', 'about', 'contact', 'terms', 'privacy', 'pricing', 'features'];

        if (in_array($username, $reserved)) {
            return response()->json([
                'available' => false,
                'message' => 'This username is reserved.',
            ]);
        }

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This username is already taken.' : 'Username is available!',
        ]);
    }
}
