<?php

namespace App\Console\Commands;

use Cart;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        Cart::instance('shopping')->add('192ao12', 'Product 1', 1, 9.99);
//        Cart::instance('shopping')->store('admin');
        Cart::instance('shopping')->restore('admin');
       // dump(Cart::content());


        return Command::SUCCESS;
    }
}
