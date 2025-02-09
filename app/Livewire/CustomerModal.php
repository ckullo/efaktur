<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\CustomerDetail;

class CustomerModal extends Component
{
    use WithPagination;

    public $id_m_customer;

    protected $paginationTheme = 'tailwind'; // ✅ Prevents main pagination from being affected

    public function mount($id_m_customer)
    {
        $this->id_m_customer = $id_m_customer;
    }

    public function render()
    {
        $customer = Customer::find($this->id_m_customer);
        $customerDetails = CustomerDetail::where('id_m_customer', $this->id_m_customer)
                            ->paginate(25);

        return view('livewire.customer-modal', compact('customer', 'customerDetails'));
    }
}
