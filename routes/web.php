<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    $product = Product::create([
        'category_id' => $validated['category_id'] ?? '1',
        'name' => $validated['name'] ?? 'billu',
        'description' => $validated['description'] ?? 'tst desc',
        'sku' => $validated['sku'] ?? 'b-2343',
        'price' => $validated['price'] ?? 1230,
        'stock_quantity' => $validated['stock_quantity'] ?? 120,
        'is_active' => $validated['is_active'] ?? true,
    ]);
    dd($product);

    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::resource('blog', BlogController::class);
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])->names('products');
    Route::resource('services', ServiceController::class);
});

require __DIR__.'/settings.php';
