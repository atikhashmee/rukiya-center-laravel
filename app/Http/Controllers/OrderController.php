<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected array $orderStatuses = ['pending', 'paid', 'processing', 'completed', 'cancelled'];
    protected array $paymentStatuses = ['pending', 'paid', 'failed'];

    public function index(Request $request)
    {
        $query = Order::withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('orders/index', [
            'orders' => $orders,
            'orderStatuses' => $this->orderStatuses,
            'paymentStatuses' => $this->paymentStatuses,
            'filters' => $request->only(['search', 'status', 'payment_status']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return Inertia::render('orders/show', [
            'order' => $order,
            'orderStatuses' => $this->orderStatuses,
            'paymentStatuses' => $this->paymentStatuses,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', $this->orderStatuses),
            'payment_status' => 'required|in:' . implode(',', $this->paymentStatuses),
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', "Order **{$order->order_number}** updated successfully.");
    }
}
