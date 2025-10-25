<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index($name, Request $request)
    {
        dd($request->all(), $name);
    }
}
