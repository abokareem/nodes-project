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
        $first = Masternode::create([
            'name' => 'first',
            'description' => 'first description',
            'state' => 'new',
            'income' => '0.001',
            'price' => '10'
        ]);
        $first->share()->create([
            'price' => '0.1',
            'count' => '100'
        ]);
        $first->bill()->create([
            'currency_id' => 1,
            'amount' => '1'
        ]);

        $second = Masternode::create([
            'name' => 'second',
            'description' => 'second description',
            'state' => 'active',
            'income' => '0.0001',
            'price' => '15'
        ]);
        $second->share()->create([
            'price' => '1',
            'count' => '15'
        ]);
        $second->bill()->create([
            'currency_id' => 2,
            'amount' => '5'
        ]);

        factory(Masternode::class, 3)->create()->each(function ($node) {
            $node->share()->save(factory(\App\MasternodeShare::class)->make());
            $node->bill()->save(factory(\App\MasternodeBill::class)->make());
        });
    }
}
