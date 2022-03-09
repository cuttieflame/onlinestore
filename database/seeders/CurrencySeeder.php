<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('currencies')->truncate();
        \DB::table('currencies')->insert([
            [
                'code'=>'RUB',
                'symbol'=>'₽',
                'is_main'=>1,
                'rate'=>1,
            ],
            [
                'code'=>'USD',
                'symbol'=>'$',
                'is_main'=>0,
                'rate'=>91,
            ],
            [
                'code'=>'EUR',
                'symbol'=>'€',
                'is_main'=>0,
                'rate'=>105,
            ]
        ]);
    }
}
