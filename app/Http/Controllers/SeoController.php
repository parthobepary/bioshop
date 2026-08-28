<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate dynamic sitemap
     */
    public function sitemap(): Response
    {
        $profiles = Profile::where('is_active', true)
            ->select('username', 'updated_at')
            ->get();

        $content = view('seo.sitemap', [
            'profiles' => $profiles,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate robots.txt
     */
    public function robots(): Response
    {
        $content = view('seo.robots')->render();

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
