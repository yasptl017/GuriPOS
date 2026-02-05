<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrdersExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    /**
     * DataGrid instance
     *
     * @var mixed
     */
    protected $gridData = array();
    protected $headings = array();

    /**
     * Create a new instance.
     *
     * @param mixed DataGrid
     * @return void
     */
    public function __construct($gridData,$headings)
    {
        $this->gridData = $gridData;
        $this->headings = $headings;
    }

    public function headings(): array {
        return $this->headings;
      }

      /**
      * @return \Illuminate\Support\Collection
      */
      public function collection() {
        return collect($this->gridData);
      }
}
