<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Modules\POS\POSTable;
use Session;

class TableManager extends Component
{

    public $active_table;


    public function select_table($id)
    {
        Session::put('active_table', $id);
        $this->active_table = POSTable::find($id);
        $this->emit('table-selected', $this->active_table->meta);
    }


    public function mount()
    {
        $table_id = Session::get('active_table');
        $activeTable = POSTable::find($table_id);
        if (!$activeTable) {
            $activeTable = POSTable::first();
            Session::put('active_table', $activeTable->id);
        }
        $this->active_table = $activeTable;
    }

    public function update_payment_status($payment_status)
    {
        if (!$this->active_table) return;
        $meta = $this->active_table->meta;
        $meta['payment_status'] = $payment_status;

        $this->active_table->meta = $meta;

        $this->active_table->save();

    }

    public function update_payment_method($payment_method)
    {
        if (!$this->active_table) return;
        $meta = $this->active_table->meta;
        $meta['payment_method'] = $payment_method;

        $this->active_table->meta = $meta;

        $this->active_table->save();
    }

    public function update_customer_id($customer_id)
    {
        if (!$this->active_table) return;
        $meta = $this->active_table->meta;
        $meta['customer_id'] = $customer_id;

        $this->active_table->meta = $meta;

        $this->active_table->save();

    }

    public function update_order_option($order_option)
    {
        if (!$this->active_table) return;
        $meta = $this->active_table->meta;
        $meta['order_type'] = $order_option;

        $this->active_table->meta = $meta;

        $this->active_table->save();

    }


    public function render()
    {


        $active_postable = $this->active_table;

        return view('livewire.table-manager', [
            'tables' => POSTable::all(),
            'meta' => [
                'order_type' => isset($active_postable->meta['order_type']) ? $active_postable->meta['order_type'] : 'dineIn',
                'customer_id' => isset($active_postable->meta['customer_id']) ? $active_postable->meta['customer_id'] : 2,
                'payment_status' => isset($active_postable->meta['payment_status']) ? $active_postable->meta['payment_status'] : 'paid',
                'payment_method' => isset($active_postable->meta['payment_method']) ? $active_postable->meta['payment_method'] : 'card',
                'walking_customer_id' => 2
            ]
        ]);
    }
}
