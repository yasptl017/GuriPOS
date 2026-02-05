<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\POS\POSTable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        POSTable::query()->delete();

        foreach (range(1, 12) as $i) {
            POSTable::create([
                'name' => $i,
                'meta' => [
                ],
                'cart' => [
                ],
                'resolved_order' => [
                ],
            ]);
        }
    }
}
