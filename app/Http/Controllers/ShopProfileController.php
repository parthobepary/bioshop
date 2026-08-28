<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ShopProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return Inertia::render('Dashboard/Settings/Profile', [
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('profiles', 'username')->ignore($profile->id),
            ],
            'name' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'whatsapp' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'theme_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'social_links' => ['nullable', 'array'],
            'social_links.facebook' => ['nullable', 'url'],
            'social_links.instagram' => ['nullable', 'url'],
            'social_links.youtube' => ['nullable', 'url'],
            'social_links.tiktok' => ['nullable', 'url'],
            'social_links.twitter' => ['nullable', 'url'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ], [
            'username.regex' => 'Username can only contain lowercase letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'whatsapp.regex' => 'Please enter a valid Bangladesh phone number (e.g., 01712345678).',
            'theme_color.regex' => 'Please select a valid color.',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $validated['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $profile->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function deletePhoto()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
            $profile->update(['photo' => null]);
        }

        return back()->with('success', 'Photo deleted successfully!');
    }
}
