<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Masternode;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first = User::create([
            'name' => 'first',
            'email' => 'first@example.com',
            'password' => 12341234,
            'group_id' => 1,
            'language' => 'ru',
            'email_confirmed' => true,
        ]);
        $first->bills()->create([
            'currency_id' => 1,
            'uuid' => md5(uniqid()),
            'amount' => '0.01'
        ]);

        $second = User::create([
            'name' => 'second',
            'email' => 'second@example.com',
            'password' => 12341234,
            'group_id' => 2,
            'language' => 'en',
            'email_confirmed' => true,
        ]);
        $second->bills()->create([
            'currency_id' => 1,
            'uuid' => md5(uniqid()),
            'amount' => '0.01'
        ]);

        $third = User::create([
            'name' => 'third',
            'email' => 'third@example.com',
            'password' => 12341234,
            'group_id' => 2,
            'language' => 'ru',
            'email_confirmed' => true,
        ]);

        $third->bills()->create([
            'currency_id' => 1,
            'uuid' => md5(uniqid()),
            'amount' => '10'
        ]);


        factory(\App\Currency::class, 1)->create()->each(
            function ($currency) use ($first, $second, $third) {

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
                    'amount' => 18
                ]);
                $first->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 8
                ]);
                $second->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 6
                ]);
                $third->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 4
                ]);
                $third->withdrawals()->create([
                    'node_id' => $node->id,
                    'state' => \App\Withdrawals::PROCESSING_STATE,
                    'amount' => 4
                ]);
            });

        factory(\App\Currency::class, 1)->create()->each(
            function ($currency) use ($first, $second, $third) {

                $share = $currency->share()->create([
                    'currency_id' => $currency->id,
                    'share_price' => 2,
                    'full_price' => 200
                ]);
                $node = $currency->nodes()->create([
                    'state' => Masternode::PROCESSING_STATE,
                    'type' => Masternode::PARTY_TYPE,
                    'price' => $share->full_price
                ]);
                $node->bill()->create([
                    'amount' => $share->full_price
                ]);
                $first->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 100
                ]);
                $second->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 50
                ]);
                $third->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 50
                ]);
            });

        factory(\App\Currency::class, 1)->create()->each(
            function ($currency) use ($first, $second, $third) {

                $share = $currency->share()->create([
                    'currency_id' => $currency->id,
                    'share_price' => 2,
                    'full_price' => 250
                ]);
                $node = $currency->nodes()->create([
                    'state' => Masternode::STABLE_STATE,
                    'type' => Masternode::PARTY_TYPE,
                    'price' => $share->full_price
                ]);
                $node->bill()->create([
                    'amount' => $share->full_price
                ]);
                $first->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 100
                ]);
                $second->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 50
                ]);
                $third->investments()->create([
                    'node_id' => $node->id,
                    'currency_id' => $currency->id,
                    'amount' => 100
                ]);
            });
    }
}
