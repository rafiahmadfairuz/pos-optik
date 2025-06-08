<?php

namespace App\Livewire;

use Livewire\Component;

class SelectedCustomer extends Component
{
    public $customer;

    protected $listeners = ['customerSelected'];

    public function customerSelected($customer)
    {
        $this->customer = $customer;

         $this->dispatch('customerDataSent', $customer);
    }

    public function render()
    {
        return view('livewire.selected-customer');
    }
}

