<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\User;
use App\Models\Resep;
use App\Models\Orderan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('cabang_id', session('cabang_id'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->get();

        return view("Informasi.customer", compact("customers"));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'nullable|email|max:100|unique:users,email',
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',
                'alamat' => 'nullable|string|max:255',
                'umur' => 'nullable',
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

    public function show(string $id)
    {
        $customer = User::findOrFail($id);

        // Ambill riwayat orderan user
        $orderan = Orderan::where("user_id", $id)
            ->orderByDesc('created_at')
            ->get();

        // AMBIL RIWAYAT RESEP USER BERDASARKAN USER_ID
        $reseps = Resep::where("user_id", $id)
            ->with('staff')
            ->orderByDesc('tanggal_pemeriksaan')
            ->orderByDesc('created_at')
            ->get();

        return view('Informasi.detailUser', compact('customer', 'orderan', 'reseps'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'nullable|email|max:100|unique:users,email,' . $id,
                'phone' => 'required|regex:/^[0-9+\-\s()]*$/|max:20',
                'alamat' => 'nullable|string|max:255',
                'umur' => 'nullable',
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
            return back()->with('error', 'Gagal memperbarui Customer. ' . $e->getMessage());
        }
    }

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
