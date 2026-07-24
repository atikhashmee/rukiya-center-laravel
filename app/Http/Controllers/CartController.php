<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = collect();
        $total = 0;

        if (!empty($cart)) {
            $ids = array_keys($cart);
            $products = Product::with(['category', 'images'])
                ->whereIn('id', $ids)
                ->get()
                ->map(function ($product) use ($cart, &$total) {
                    $qty = $cart[$product->id];
                    $product->quantity = $qty;
                    $product->line_total = $product->price * $qty;
                    $total += $product->line_total;
                    return $product;
                });
        }

        return view('Themes.cart', compact('products', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->withErrors(['product' => 'This product is not available.']);
        }

        $cart = session()->get('cart', []);
        $qty = (int) $request->quantity;

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $qty;
        } else {
            $cart[$product->id] = $qty;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $cart[$request->product_id] = (int) $request->quantity;
        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }
}
