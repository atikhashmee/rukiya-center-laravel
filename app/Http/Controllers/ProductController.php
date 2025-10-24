<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->orderBy('name')->paginate(10);

        return Inertia::render('products/index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

        return Inertia::render('products/create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products|max:50',
            'price' => 'required|numeric|min:0.01',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',

            'images' => 'nullable|array|max:5', // Max 5 images per product
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5000',
        ]);

        // 2. Transaction Start
        DB::beginTransaction();
        try {
            // 3. Create Product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // 4. Handle Image Uploads
            if ($request->hasFile('images')) {
                $sortOrder = 0;
                foreach ($request->file('images') as $imageFile) {
                    // Store the image in the 'public/products' directory
                    $path = $imageFile->store('products', 'public');

                    // Create the ProductImage record
                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'path' => Storage::url($path), // Get the public URL
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            DB::commit(); // Commit transaction

            return response()->json([
                'message' => 'Product created successfully, including images.',
                'product' => $product->load('category', 'images'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error

            // In a real app, you might also want to delete any files uploaded before the exception
            return response()->json(['message' => 'Product creation failed.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource. (READ - Single)
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource. (Not typically used in API)
     */
    public function edit(string $id)
    {
        // For a full web application, this returns the edit form view.
        return response()->json(['message' => 'Not implemented for API endpoint.'], 405);
    }

    /**
     * Update the specified resource in storage. (UPDATE)
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validation
        $validated = $request->validate([
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',

            // Validate SKU uniqueness, ignoring the current product's ID
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('products')->ignore($product->product_id, 'product_id'),
            ],

            'price' => 'sometimes|required|numeric|min:0.01',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'is_active' => 'nullable|boolean',

            // New images: same validation as store
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5000',

            // Optional: for deleting existing images
            'delete_image_ids' => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:product_images,id',
        ]);

        // 2. Transaction Start
        DB::beginTransaction();
        try {
            // 3. Update Product details
            $product->update($validated);

            // 4. Handle Image Deletions
            if (! empty($validated['delete_image_ids'])) {
                $imagesToDelete = ProductImage::whereIn('id', $validated['delete_image_ids'])
                    ->where('product_id', $product->product_id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    // Delete file from storage
                    $path = str_replace(Storage::url('/'), '', $image->path);
                    Storage::disk('public')->delete($path);

                    // Delete database record
                    $image->delete();
                }
            }

            // 5. Handle New Image Uploads (re-using logic from store)
            if ($request->hasFile('new_images')) {
                // Get the current max sort order and continue from there
                $sortOrder = $product->images()->max('sort_order') + 1;

                foreach ($request->file('new_images') as $imageFile) {
                    $path = $imageFile->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'path' => Storage::url($path),
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }

            DB::commit(); // Commit transaction

            return response()->json([
                'message' => 'Product updated successfully.',
                'product' => $product->load('category', 'images'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Product update failed.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage. (DELETE)
     */
    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        // 1. Transaction Start
        DB::beginTransaction();
        try {
            // 2. Delete all related image files from storage
            foreach ($product->images as $image) {
                // The 'path' stores the full URL (e.g., /storage/products/xyz.jpg).
                // We need the internal path (products/xyz.jpg).
                $path = str_replace(Storage::url('/'), '', $image->path);
                Storage::disk('public')->delete($path);
            }

            // Because of the 'onDelete('cascade')' in the migration,
            // the ProductImage records will be deleted automatically.

            // 3. Delete the Product
            $product->delete();

            DB::commit(); // Commit transaction

            return response()->json(['message' => 'Product and all related images deleted successfully.'], 204);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Product deletion failed.', 'error' => $e->getMessage()], 500);
        }
    }
}
