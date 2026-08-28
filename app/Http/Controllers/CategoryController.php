<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;
        $categories = $profile->categories()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Dashboard/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        // Get the next sort order
        $maxSortOrder = $profile->categories()->max('sort_order') ?? 0;

        $category = $profile->categories()->create([
            'name' => $validated['name'],
            'sort_order' => $maxSortOrder + 1,
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, Category $category)
    {
        // Ensure the category belongs to the user's profile
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Ensure the category belongs to the user's profile
        $this->authorize('delete', $category);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Move or delete products first.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'categories' => ['required', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:categories,id'],
            'categories.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['categories'] as $categoryData) {
            $category = Category::find($categoryData['id']);

            // Verify ownership
            if ($category && $category->profile_id === $profile->id) {
                $category->update(['sort_order' => $categoryData['sort_order']]);
            }
        }

        return back()->with('success', 'Categories reordered!');
    }
}
