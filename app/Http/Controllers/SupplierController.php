<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('TransferBarang.listSupplier', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users,email',
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',
            ]);

            Supplier::create([
                ...$validated,
            ]);

            return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi tambah Supplier gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create Supplier gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan Supplier.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::find($id);
        $pembelian = Pembelian::where("supplier_id", $id)->get();
        return view('TransferBarang.detailSupplier', compact('supplier', 'pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users,email,' . $id,
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',
            ]);

            $supplier = Supplier::findOrFail($id);

            $supplier->name = $validated['name'];
            $supplier->email = $validated['email'];
            $supplier->phone = $validated['phone'] ?? null;

            $supplier->save();

            return redirect()->back()->with('success', 'Customer berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi update customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.')->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update customer gagal: Customer tidak ditemukan. ID=' . $id);
            return back()->with('error', 'Customer tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Update customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui Supplier.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete Supplier gagal: Supplier tidak ditemukan. ID=' . $id);
            return back()->with('error', 'Supplier tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Delete Supplier gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus Supplier.');
        }
    }
}
