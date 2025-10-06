<?php

namespace App\Http\Controllers\Company;

use App\Models\Staff;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffs = Staff::where('role', '!=', 'admin')->get();
        $cabangs = Cabang::all();
        return view("Admin.staffList", compact("staffs", "cabangs"));
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
                'name' => 'required|string|max:50',
                'email' => 'required|unique:staff,email|max:255',
                'password' => 'required|string|min:4',
                'role' => 'required|in:gudang_cabang,gudang_utama,cabang',
                'cabang' => in_array($request->role, ['cabang', 'gudang_cabang']) ? 'required|exists:cabangs,id' : 'nullable',
            ]);
            $newDataStaff = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ];

            if ($validated['role'] === 'cabang') {
                $newDataStaff['cabang_id'] = $validated['cabang'];
            }

            Staff::create($newDataStaff);

            return back()->with('success', 'Staff berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
dd($request->all());

            Log::error('Create staff failed (validation): ' . $e->getMessage());
            return back()->with('error', 'Validasi gagal.')->withInput();
        } catch (\Exception $e) {
            Log::error('Create staff failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:gudang_cabang,gudang_utama,cabang',
            'cabang' => 'nullable|exists:cabangs,id',
        ]);

        try {
            $staff = Staff::findOrFail($id);

            $staff->name = $validated['name'];
            $staff->email = $validated['email'] ?? $staff->email;
            if (!empty($validated['password'])) {
                $staff->password = bcrypt($validated['password']);
            }
            $staff->role = $validated['role'];
            $staff->cabang_id = $validated['cabang'] ?? null;

            $staff->save();

            return back()->with('success', 'Staff berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Update staff failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Staff tidak ditemukan.')->withInput();
        } catch (\Exception $e) {
            Log::error('Update staff failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui staff. Silahkan coba kembali.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();
            return back()->with('success', 'Staff berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Delete staff failed - not found: ' . $e->getMessage());
            return back()->with('error', 'Staff tidak ditemukan.')->withInput();
        } catch (\Exception $e) {
            Log::error('Delete staff failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus staff. Tolong coba kembali.')->withInput();
        }
    }
}
