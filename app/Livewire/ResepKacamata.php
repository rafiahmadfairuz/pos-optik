<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Resep;
use App\Models\Staff;

class ResepKacamata extends Component
{
    public $opsi_resep = 'tanpa_resep';
    public $selected_resep_id;
    public $user_id;
    public $customer_name;

    public $tanggal_pemeriksaan;
    public $staff_id;
    public $notes;

    public $od_sph = '0.00', $od_cyl = '0.00', $od_axis, $od_add = '0.00', $od_prisma = '0.00', $od_base, $od_va;
    public $os_sph = '0.00', $os_cyl = '0.00', $os_axis, $os_add = '0.00', $os_prisma = '0.00', $os_base, $os_va;
    public $pdr, $pdl, $pv, $frame_a, $frame_b, $frame_d, $frame_diag, $frame;

    protected $listeners = [
        'customerDataSent' => 'handleCustomerSelected',
        'customerSelected' => 'handleCustomerSelected',
    ];

    // HOOK AUTOMATIS: Jalankan pengiriman data setiap ada perubahan field di form resep
    public function updated($propertyName)
    {
        $this->emitResepDataToParent();
    }

    public function updatedOpsiResep($value)
    {
        $this->resetFormResep();

        if ($value === 'resep_baru') {
            $this->tanggal_pemeriksaan = date('Y-m-d');
        }

        $this->dispatch('resepOptionUpdated', $value);
        $this->emitResepDataToParent();
    }

    private function emitResepDataToParent()
    {
        // PERUBAHAN KECIL: Kirim data juga jika opsi 'resep_lama' terpilih agar parent tahu data staff/resepnya
        if (in_array($this->opsi_resep, ['resep_baru', 'resep_lama'])) {
            $dataResepBaru = [
                'staff_id'            => $this->staff_id,
                'tanggal_pemeriksaan' => $this->tanggal_pemeriksaan ?? date('Y-m-d'),
                'notes'               => $this->notes,
                'od_sph'              => $this->od_sph,
                'od_cyl'              => $this->od_cyl,
                'od_axis'             => $this->od_axis,
                'od_add'              => $this->od_add,
                'od_prisma'           => $this->od_prisma,
                'od_base'             => $this->od_base,
                'od_va'               => $this->od_va,
                'os_sph'              => $this->os_sph,
                'os_cyl'              => $this->os_cyl,
                'os_axis'             => $this->os_axis,
                'os_add'              => $this->os_add,
                'os_prisma'           => $this->os_prisma,
                'os_base'             => $this->os_base,
                'os_va'               => $this->os_va,
                'pdr'                 => $this->pdr,
                'pdl'                 => $this->pdl,
                'pv'                  => $this->pv,
                'frame_a'             => $this->frame_a,
                'frame_b'             => $this->frame_b,
                'frame_d'             => $this->frame_d,
                'frame_diag'          => $this->frame_diag,
                'frame'               => $this->frame,
            ];

            // Lempar data resep lengkap ke TransactionDetail
            $this->dispatch('resepDataUpdated', $dataResepBaru);
        }
    }

    public function handleCustomerSelected($customer)
    {
        if (is_array($customer)) {
            $this->user_id = $customer['id'] ?? null;
            $this->customer_name = $customer['name'] ?? null;
        } elseif (is_object($customer)) {
            $this->user_id = $customer->id ?? null;
            $this->customer_name = $customer->name ?? null;
        } else {
            $this->user_id = $customer;
        }

        $this->resetFormResep();

        if ($this->opsi_resep === 'resep_lama') {
            $this->selected_resep_id = null;
        }
    }

    public function mount($userId = null)
    {
        $this->user_id = $userId;
    }

    public function updatedSelectedResepId($resepId)
    {
        if ($resepId) {
            $resep = Resep::find($resepId);

            if ($resep) {
                $this->tanggal_pemeriksaan = $resep->tanggal_pemeriksaan;
                $this->staff_id            = $resep->staff_id;
                $this->notes               = $resep->notes;

                $this->od_sph    = $resep->od_sph ?? '0.00';
                $this->od_cyl    = $resep->od_cyl ?? '0.00';
                $this->od_axis   = $resep->od_axis;
                $this->od_add    = $resep->od_add ?? '0.00';
                $this->od_prisma = $resep->od_prisma ?? '0.00';
                $this->od_base   = $resep->od_base;
                $this->od_va     = $resep->od_va;

                $this->os_sph    = $resep->os_sph ?? '0.00';
                $this->os_cyl    = $resep->os_cyl ?? '0.00';
                $this->os_axis   = $resep->os_axis;
                $this->os_add    = $resep->os_add ?? '0.00';
                $this->os_prisma = $resep->os_prisma ?? '0.00';
                $this->os_base   = $resep->os_base;
                $this->os_va     = $resep->os_va;

                $this->pdr        = $resep->pdr;
                $this->pdl        = $resep->pdl;
                $this->pv         = $resep->pv;
                $this->frame_a    = $resep->frame_a;
                $this->frame_b    = $resep->frame_b;
                $this->frame_d    = $resep->frame_d;
                $this->frame_diag = $resep->frame_diag;
                $this->frame      = $resep->frame;

                $this->dispatch('resepSelectedFromHistory', $resepId);

                // PERUBAHAN KECIL: Panggil emit agar parent langsung update data staff resep lama
                $this->emitResepDataToParent();
            }
        } else {
            $this->resetFormResep();
        }
    }

    private function resetFormResep()
    {
        $this->reset([
            'selected_resep_id', 'tanggal_pemeriksaan', 'staff_id', 'notes',
            'od_axis', 'od_base', 'od_va',
            'os_axis', 'os_base', 'os_va',
            'pdr', 'pdl', 'pv', 'frame_a', 'frame_b', 'frame_d', 'frame_diag', 'frame'
        ]);

        $this->od_sph = '0.00'; $this->od_cyl = '0.00'; $this->od_add = '0.00'; $this->od_prisma = '0.00';
        $this->os_sph = '0.00'; $this->os_cyl = '0.00'; $this->os_add = '0.00'; $this->os_prisma = '0.00';
    }

    public function render()
    {
        $listResepLama = [];

        if ($this->opsi_resep === 'resep_lama' && $this->user_id) {
            $listResepLama = Resep::where('user_id', $this->user_id)
                ->orderBy('tanggal_pemeriksaan', 'desc')
                ->get();
        }

        $optometristList = Staff::where('cabang_id', session('cabang_id'))->get();

        return view('livewire.resep-kacamata', [
            'listResepLama'   => $listResepLama,
            'optometristList' => $optometristList,
        ]);
    }
}
