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
        factory(\App\Currency::class, 1)->create()->each(function ($currency) {

            $share = $currency->share()->create([
                'currency_id' => $currency->id,
                'share_price' => 2,
                'full_price' => 200
            ]);
            $node = $currency->nodes()->create([
                'state' => Masternode::NEW_STATE,
                'type' => Masternode::PARTY_TYPE,
                'price' => $share->full_price
            ]);
            $node->bill()->create([
                'amount' => 50
            ]);
        });

        factory(\App\Currency::class, 1)->create()->each(function ($currency) {

            $share = $currency->share()->create([
                'currency_id' => $currency->id,
                'share_price' => 2,
                'full_price' => 200
            ]);
            $node = $currency->nodes()->create([
                'state' => Masternode::PROCESSING_STATE,
                'type' => Masternode::SINGLE_TYPE,
                'price' => $share->full_price
            ]);
            $node->bill()->create([
                'amount' => $share->full_price
            ]);
        });
    }
}
