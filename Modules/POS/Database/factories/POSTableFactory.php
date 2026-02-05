<?php

namespace Modules\POS\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\POS\POSTable;

class POSTableFactory extends Factory
{
    protected $model = POSTable::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'meta' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
