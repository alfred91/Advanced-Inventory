<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('products')->get();
        $defaultImagePath = asset('images/Default.png');

        return view('suppliers.index', compact('suppliers', 'defaultImagePath'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $validatedData['image'] = $request->file('image') ? $request->file('image')->store('suppliers', 'public') : null;

        Supplier::create($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('suppliers', 'public');
        }

        $supplier->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
