<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Customer::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $customer = Customer::create($validatedData);
        return response()->json($customer, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Customer::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);

        return response()->json($customer, 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::destroy($id);
        return response()->json(null, 204);
    }
}
