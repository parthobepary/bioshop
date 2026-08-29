<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $profile = Auth::user()->profile;

        $query = $profile->products()
            ->with(['category', 'images'])
            ->orderBy('sort_order');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $products = $query->get();
        $categories = $profile->categories()->orderBy('sort_order')->get();

        return Inertia::render('Dashboard/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category' => $request->category,
                'status' => $request->status,
            ],
        ]);
    }

    public function create()
    {
        $profile = Auth::user()->profile;

        // Check plan limits
        $plan = Auth::user()->activeSubscription?->plan;
        $maxProducts = $plan?->max_products ?? 5;

        if ($maxProducts !== -1 && $profile->products()->count() >= $maxProducts) {
            return redirect()->route('products.index')
                ->with('error', "You've reached your plan's limit of {$maxProducts} products. Upgrade to add more.");
        }

        $categories = $profile->categories()->orderBy('sort_order')->get();

        return Inertia::render('Dashboard/Products/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        // Check plan limits
        $plan = Auth::user()->activeSubscription?->plan;
        $maxProducts = $plan?->max_products ?? 5;

        if ($maxProducts !== -1 && $profile->products()->count() >= $maxProducts) {
            return back()->with('error', "You've reached your plan's limit of {$maxProducts} products.");
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'max:9999999.99', 'gt:price'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::in(['available', 'stock_out', 'pre_order'])],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['string', 'max:255', 'regex:#^uploads/#'], // Spaces path
        ]);

        // Verify category belongs to user's profile
        if ($validated['category_id']) {
            $categoryBelongsToProfile = $profile->categories()
                ->where('id', $validated['category_id'])
                ->exists();

            if (!$categoryBelongsToProfile) {
                return back()->with('error', 'Invalid category selected.');
            }
        }

        // Get the next sort order
        $maxSortOrder = $profile->products()->max('sort_order') ?? 0;

        $product = $profile->products()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
        ]);

        // Persist images already uploaded to Spaces by the client
        $sortOrder = 1;
        foreach ($validated['images'] ?? [] as $path) {
            $product->images()->create([
                'url' => $path,
                'sort_order' => $sortOrder++,
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $profile = Auth::user()->profile;
        $categories = $profile->categories()->orderBy('sort_order')->get();

        $product->load('images');

        return Inertia::render('Dashboard/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'max:9999999.99', 'gt:price'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::in(['available', 'stock_out', 'pre_order'])],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['string', 'max:255', 'regex:#^uploads/#'], // Spaces path
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['integer', 'exists:product_images,id'],
        ]);

        // Verify category belongs to user's profile
        if ($validated['category_id']) {
            $categoryBelongsToProfile = $profile->categories()
                ->where('id', $validated['category_id'])
                ->exists();

            if (!$categoryBelongsToProfile) {
                return back()->with('error', 'Invalid category selected.');
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
        ]);

        // Handle image deletions - remove images not in existing_images
        $existingImageIds = $validated['existing_images'] ?? [];
        $imagesToDelete = $product->images()
            ->whereNotIn('id', $existingImageIds)
            ->get();

        foreach ($imagesToDelete as $image) {
            Media::delete($image->url);
            $image->delete();
        }

        // Persist newly added images (already uploaded to Spaces)
        $maxSortOrder = $product->images()->max('sort_order') ?? 0;
        foreach ($validated['images'] ?? [] as $path) {
            $product->images()->create([
                'url' => $path,
                'sort_order' => ++$maxSortOrder,
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Delete all product images from storage
        foreach ($product->images as $image) {
            Media::delete($image->url);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    public function toggle(Product $product)
    {
        $this->authorize('update', $product);

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', $product->is_active ? 'Product enabled!' : 'Product disabled!');
    }

    public function reorder(Request $request)
    {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'integer', 'exists:products,id'],
            'products.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['id']);

            // Verify ownership
            if ($product && $product->profile_id === $profile->id) {
                $product->update(['sort_order' => $productData['sort_order']]);
            }
        }

        return back()->with('success', 'Products reordered!');
    }
}
