<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('customers/index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone_prefix' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'interests' => 'nullable|array',
            'password' => ['required', 'confirmed', Password::min(8)],
            'about' => 'nullable|string',
            'email_verified_at' => 'nullable|date',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Encode interests to JSON
        if (isset($validated['interests'])) {
            $validated['interests'] = json_encode($validated['interests']);
        }

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully!');
    }

    public function edit(int $id)
    {
        $customer = Customer::findOrFail($id);
        
        // Decode JSON field for the form
        $customerData = $customer->toArray();
        $customerData['interests'] = is_string($customer->interests) 
            ? json_decode($customer->interests, true) 
            : $customer->interests;

        return Inertia::render('customers/edit', [
            'customer' => $customerData
        ]);
    }

    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone_prefix' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'interests' => 'nullable|array',
            'about' => 'nullable|string',
        ]);
        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    public function toggleActive(int $id)
    {
        $customer = Customer::findOrFail($id);
        
        // Toggle active status using timestamps or add an 'is_active' column
        // For now, we'll add a soft delete approach or use a custom field
        // You may need to add 'is_active' boolean column to your migration
        
        $customer->update([
            'is_active' => !($customer->is_active ?? true)
        ]);

        $status = $customer->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Customer {$status} successfully!");
    }

    public function verifyEmail(int $id)
    {
        $customer = Customer::findOrFail($id);
        
        $customer->email_verified_at = now();
        $customer->save();
        $status = $customer->email_verified_at ? 'verified' : 'unverified';

        return back()->with('success', "Customer email {$status} successfully!");
    }
}
