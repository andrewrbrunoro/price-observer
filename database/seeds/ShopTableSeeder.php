<?php

use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    private $shops = [
        [
            'name' => 'Pichau',
            'job'  => 'App\Jobs\PichauJob'
        ],
        [
            'name' => 'Magazine Luiza',
            'job'  => ''
        ],
        [
            'name' => 'Americanas',
            'job'  => ''
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->shops as $shop) {
            \App\Shop::firstOrCreate($shop);
        }
    }
}
