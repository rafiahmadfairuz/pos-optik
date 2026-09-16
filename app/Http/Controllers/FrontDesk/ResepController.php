<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Resep;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ResepController extends Controller
{
    public function edit($id)
    {
        $resep = Resep::with(['user', 'staff'])->findOrFail($id);
        $optometristList = Staff::where('cabang_id', session('cabang_id'))->get();

        return view("Informasi.detailResep", compact('resep', 'optometristList'));
    }

    public function update(Request $request, $id)
    {
        $resep = Resep::findOrFail($id);

        $validatedData = $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'staff_id'            => 'required|exists:staff,id',

            // SPH: +20.00 s/d -20.00
            'od_sph'  => 'nullable|numeric|between:-20,20',
            'os_sph'  => 'nullable|numeric|between:-20,20',

            // CYL: 0.00 s/d -6.00
            'od_cyl'  => 'nullable|numeric|between:-6,0',
            'os_cyl'  => 'nullable|numeric|between:-6,0',

            // AXIS: 0 s/d 180 (Ketikan manual)
            'od_axis' => 'nullable|integer|between:0,180',
            'os_axis' => 'nullable|integer|between:0,180',

            // ADD: 0.00 s/d +4.00
            'od_add'  => 'nullable|numeric|between:0,4',
            'os_add'  => 'nullable|numeric|between:0,4',

            // PRISMA: 0.00 s/d 10.00
            'od_prisma' => 'nullable|numeric|between:0,10',
            'os_prisma' => 'nullable|numeric|between:0,10',

            // BASE: In, Out, Up, Down
            'od_base' => ['nullable', Rule::in(['In', 'Out', 'Up', 'Down'])],
            'os_base' => ['nullable', Rule::in(['In', 'Out', 'Up', 'Down'])],

            // VA: Text
            'od_va'   => 'nullable|string|max:50',
            'os_va'   => 'nullable|string|max:50',

            // Catatan
            'notes'   => 'nullable|string|max:1000',

            // Data Precal
            'pdr'        => 'nullable|numeric|min:0',
            'pdl'        => 'nullable|numeric|min:0',
            'pv'         => 'nullable|numeric|min:0',
            'frame_a'    => 'nullable|numeric|min:0',
            'frame_b'    => 'nullable|numeric|min:0',
            'frame_d'    => 'nullable|numeric|min:0',
            'frame_diag' => 'nullable|numeric|min:0',
            'frame'      => ['nullable', Rule::in(['Full Metal', 'Full Plastik', 'Nylon', 'Rimless'])],
        ]);

        try {
            $resep->update($validatedData);

            return redirect()->back()->with('success', 'Data resep kacamata berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update Resep Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui resep: ' . $e->getMessage())->withInput();
        }
    }
}
