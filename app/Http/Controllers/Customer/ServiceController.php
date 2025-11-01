<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

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
}
