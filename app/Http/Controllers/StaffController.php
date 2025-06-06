<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function showStaff()
    {
        $staffs = Staff::where('role', '!=', 'admin')->get();
        $cabang = Cabang::all();
        return view("staffList", compact("staffs", "cabang"));
    }
    public function createStaff(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email:rfc|unique:staff,email|max:255',
                'password' => 'required|string|min:4',
                'role' => 'required|in:gudang,cabang',
                'cabang' => $request->role === 'cabang' ? 'required|exists:cabangs,id' : 'nullable',
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

            return redirect()->route('staff')->with('success', 'Staff berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Create staff failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function editStaff(Request $request, $id)
    {
        // Validasi data masukannya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:gudang,cabang',
            'cabang' => 'nullable|exists:cabang,id',
        ]);

        try {
            $staff = Staff::findOrFail($id);

            $staff->name = $validated['name'];
            $staff->email = $validated['email'] ?? $staff->email;
            if (!empty($validated['password'])) {
                $staff->password = bcrypt($validated['password']); // hash password jika diisi
            }
            $staff->role = $validated['role'];
            $staff->cabang_id = $validated['cabang'] ?? null;

            $staff->save();

            return redirect()->back()->with('success', 'Staff updated successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Staff not found.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to update staff. Please try again.');
        }
    }

    public function deleteStaff($id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();

            return redirect()->back()->with('success', 'Staff deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->withErrors('Staff not found.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to delete staff. Please try again.');
        }
    }
}
