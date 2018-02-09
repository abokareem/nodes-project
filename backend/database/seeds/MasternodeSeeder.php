<?php

use Illuminate\Database\Seeder;
use App\Masternode;

class MasternodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Masternode::create([
            'name' => 'first',
            'description' => 'first description',
            'state' => 'new',
            'income' => '0.001',
            'price' => '10'
        ])->share()->create([
            'price' => '0.1',
            'count' => '100'
        ]);

        Masternode::create([
            'name' => 'second',
            'description' => 'second description',
            'state' => 'active',
            'income' => '0.0001',
            'price' => '15'
        ])->share()->create([
            'price' => '1',
            'count' => '15'
        ]);

        factory(Masternode::class, 3)->create()->each(function ($node) {
            $node->share()->save(factory(\App\MasternodeShare::class)->make());
        });
    }
}
