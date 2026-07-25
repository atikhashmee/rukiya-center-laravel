<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index($name, Request $request)
    {
        $services = Service::whereHas('category', fn ($q) => $q->where('slug', $name))->get();

        return view(Theme::resolveViewName('service-detail'), [
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

        return view(Theme::resolveViewName('customer.my-booking'), compact('bookings'));
    }

    public function myTransactions(Request $request)
    {
        $transactions = Payment::where('customer_id', auth('customer')->id())->get();

        return view(Theme::resolveViewName('customer.my-transactions'), compact('transactions'));
    }
}
