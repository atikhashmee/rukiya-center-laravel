<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Theme;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartData()
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

        return ['products' => $products, 'total' => $total, 'cart' => $cart];
    }

    public function index()
    {
        $data = $this->getCartData();
        return view(Theme::resolveViewName('cart'), ['products' => $data['products'], 'total' => $data['total']]);
    }

    public function checkout()
    {
        $data = $this->getCartData();
        if ($data['products']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        return view(Theme::resolveViewName('checkout-form'), ['products' => $data['products'], 'total' => $data['total']]);
    }

    public function placeOrder(Request $request)
    {
        $data = $this->getCartData();
        if ($data['products']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'email' => 'required|email|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . time(),
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'subtotal' => $data['total'],
            'total' => $data['total'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        foreach ($data['products'] as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'subtotal' => $product->line_total,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('wizard.payment.checkout', ['order' => $order->id])
            ->with('success', 'Order created! Please complete payment.');
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
