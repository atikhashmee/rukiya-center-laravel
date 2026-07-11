<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $stats = [
            'products' => [
                'total'      => Product::count(),
                'active'     => Product::where('is_active', true)->count(),
                'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            ],
            'services' => [
                'total'    => Service::count(),
                'free'     => Service::where('price_type', 'FREE')->count(),
                'paid'     => Service::whereIn('price_type', ['FIXED', 'DONATION'])->count(),
            ],
            'blogs' => [
                'total'     => BlogPost::withTrashed()->count(),
                'published' => BlogPost::where('status', 'published')->count(),
                'drafts'    => BlogPost::where('status', 'draft')->count(),
            ],
            'customers' => [
                'total'    => Customer::count(),
                'active'   => Customer::where('is_active', true)->count(),
                'verified' => Customer::whereNotNull('email_verified_at')->count(),
            ],
            'users' => [
                'total'    => User::count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ],
            'bookings' => [
                'total'   => Booking::count(),
                'new'     => Booking::where('booking_status', 'new')->count(),
                'pending' => Booking::where('payment_status', 'pending')->count(),
                'completed' => Booking::where('booking_status', 'completed')->count(),
                'revenue' => Booking::where('payment_status', 'paid')->sum('service_price'),
            ],
        ];

        $recentBookings = Booking::with('customer')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($b) => [
                'id'             => $b->id,
                'booking_id'     => $b->booking_id,
                'full_name'      => $b->full_name,
                'service_id'     => $b->service_id,
                'booking_status' => $b->booking_status,
                'payment_status' => $b->payment_status,
                'service_price'  => $b->service_price,
                'created_at'     => $b->created_at->format('d M Y'),
            ]);

        $recentCustomers = Customer::latest()
            ->take(5)
            ->get()
            ->map(fn ($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'email'     => $c->email,
                'is_active' => $c->is_active,
                'created_at' => $c->created_at->format('d M Y'),
            ]);

        return Inertia::render('dashboard', [
            'stats'           => $stats,
            'recentBookings'  => $recentBookings,
            'recentCustomers' => $recentCustomers,
        ]);
    }
}
