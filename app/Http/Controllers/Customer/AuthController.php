<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('Themes.login');
    }

    public function register(Request $request)
    {
        return view('Themes.register');
    }

    public function registerStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_prefix' => 'required',
            'phone' => 'required',
            'interests' => 'required|array',
            'password' => 'required',
            'about' => 'nullable',
        ]);
        try {
            $validated['about'] = 'ss';
            $customer = Customer::create($validated);
            event(new Registered($customer));
            return redirect(route('customer.profile'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try {
            $loggedin = Auth::guard('customer')->attempt($validated);
            if ($loggedin) {
                $request->session()->regenerate();

                return redirect()->intended('customer/profile');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());
        }
    }
}
