<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class CustomerSearch extends Component
{
    public $search = null;
    public $selectedCustomer = null;

    public function selectCustomer($customerId)
    {
        $this->selectedCustomer = User::find($customerId);

        Log::info('Customer selected:', ['customer' => $this->selectedCustomer]);

        $this->dispatch('customerSelected', customer: $this->selectedCustomer);
    }

    public function runSearch()
    {
        $this->dispatch('$refresh');
    }
    public function resetSearch()
    {
        $this->search = null;
        $this->dispatch('$refresh');
    }


    public function render()
    {
        $customers = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->paginate(3);

        Log::info('Search term:', ['search' => $this->search]);
        Log::info('Found customers:', ['customers' => $customers]);

        return view('livewire.customer-search', compact('customers'));
    }
}
