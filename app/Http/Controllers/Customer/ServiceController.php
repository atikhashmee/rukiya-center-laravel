<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index($name, Request $request)
    {
        $services = Service::where('category', $name)->get();

        return view('Themes.service-detail', [
            'services' => $services,
            'service_type' => $name,
        ]);
    }

    public function myBooking(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $bookings = Booking::where('customer_id', $customer->id)
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Themes.customer.my-booking', compact('bookings'));
    }
}
