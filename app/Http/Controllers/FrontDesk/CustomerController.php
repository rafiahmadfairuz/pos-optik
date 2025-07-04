<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Orderan;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('cabang_id', session('cabang_id'))->get();
        return view("Informasi.customer", compact("customers"));
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'nullable|email|max:100|unique:users,email',
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',

                'alamat' => 'nullable|string|max:255',
                'umur' => 'nullable|integer|min:1|max:120',
                'gender' => 'nullable|in:male,female,other',
            ]);


            User::create([
                ...$validated,
                'cabang_id' => session('cabang_id'),
            ]);

            return redirect()->back()->with('success', 'Customer berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi tambah customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan Customer.')->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = User::find($id);
        $orderan = Orderan::where("user_id", $id)->get();
        return view('Informasi.detailUser', compact('customer', 'orderan'));
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
                'email' => 'nullable|email|max:100|unique:users,email,' . $id,
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',

                'alamat' => 'nullable|string|max:255',
                'umur' => 'nullable|integer|min:1|max:120',
                'gender' => 'nullable|in:male,female,other',
            ]);


            $customer = User::findOrFail($id);

            $customer->name = $validated['name'];
            $customer->email = $validated['email'];
            $customer->phone = $validated['phone'] ?? null;
            $customer->alamat = $validated['alamat'] ?? null;
            $customer->umur = $validated['umur'] ?? null;
            $customer->gender = $validated['gender'] ?? null;
            $customer->cabang_id = session('cabang_id');

            $customer->save();

            return redirect()->back()->with('success', 'Customer berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi update customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.')->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update customer gagal: Customer tidak ditemukan. ID=' . $id);
            return back()->with('error', 'Customer tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Update customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui Customer.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = User::findOrFail($id);
            $customer->delete();

            return redirect()->back()->with('success', 'Customer berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete customer gagal: Customer tidak ditemukan. ID=' . $id);
            return back()->with('error', 'Customer tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Delete customer gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus Customer.');
        }
    }
}
