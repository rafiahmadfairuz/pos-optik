<?php

namespace App\Livewire;

use App\Models\Staff;
use App\Models\Asuransi;
use Livewire\Component;
use Illuminate\Validation\Rule;

class TransactionDetail extends Component
{
    // Prescription fields
    public $right_sph_d, $right_cyl_d, $right_axis_d, $right_va_d;
    public $left_sph_d, $left_cyl_d, $left_axis_d, $left_va_d;
    public $add_right, $add_left;
    public $pd_right, $pd_left, $notes;

    // Orderan fields
    public $order_status, $order_date, $complete_date;
    public $payment_type, $optometrist_id, $customer_paying;
    public $payment_method, $payment_status, $asuransi;

    public $asuransiList = [];
    public $optometristList = ["joko"];
    public $cabang_id;

    protected $listeners = ['setCabangId'];

    public function rules()
    {
        return [
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'order_date' => 'required|date',
            'complete_date' => 'nullable|date|after_or_equal:order_date',
            'payment_type' => ['required', Rule::in(['DP', 'pelunasan', 'asuransi'])],
            'optometrist_id' => 'required|exists:staff,id',
            'customer_paying' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid'])],
            'asuransi' => 'nullable|exists:asuransi,id',

            'right_sph_d' => 'nullable|string|max:10',
            'right_cyl_d' => 'nullable|string|max:10',
            'right_axis_d' => 'nullable|string|max:10',
            'right_va_d' => 'nullable|string|max:10',
            'left_sph_d' => 'nullable|string|max:10',
            'left_cyl_d' => 'nullable|string|max:10',
            'left_axis_d' => 'nullable|string|max:10',
            'left_va_d' => 'nullable|string|max:10',
            'add_right' => 'nullable|string|max:10',
            'add_left' => 'nullable|string|max:10',
            'pd_right' => 'nullable|string|max:10',
            'pd_left' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function setCabangId($id)
    {
        $this->cabang_id = $id;
    }

    public function submitToParent()
    {
        $this->validate();

        $this->dispatch('dataOrderOrder', [
            'order_status' => $this->order_status,
            'order_date' => $this->order_date,
            'complete_date' => $this->complete_date,
            'payment_type' => $this->payment_type,
            'optometrist_id' => $this->optometrist_id,
            'customer_paying' => $this->customer_paying,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'asuransi_id' => $this->asuransi,
        ]);

        $this->dispatch('dataOrderResep', [
            'right_sph_d' => $this->right_sph_d,
            'right_cyl_d' => $this->right_cyl_d,
            'right_axis_d' => $this->right_axis_d,
            'right_va_d' => $this->right_va_d,
            'left_sph_d' => $this->left_sph_d,
            'left_cyl_d' => $this->left_cyl_d,
            'left_axis_d' => $this->left_axis_d,
            'left_va_d' => $this->left_va_d,
            'add_right' => $this->add_right,
            'add_left' => $this->add_left,
            'pd_right' => $this->pd_right,
            'pd_left' => $this->pd_left,
            'notes' => $this->notes,
        ]);
    }

    public function render()
    {
        $this->asuransiList = Asuransi::where('cabang_id', session('cabang_id'))->get();
        $this->optometristList = session("cabang_id")
            ? Staff::where('cabang_id', $this->cabang_id)->get()
            : [];

        return view('livewire.transaction-detail');
    }
}
