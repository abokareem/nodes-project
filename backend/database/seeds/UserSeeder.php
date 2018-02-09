<?php

use Illuminate\Database\Seeder;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'first',
            'email' => 'first@example.com',
            'password' => 12341234,
            'group_id' => 1,
            'language' => 'ru',
            'email_confirmed' => true,
        ]);

        User::create([
            'name' => 'second',
            'email' => 'second@example.com',
            'password' => 12341234,
            'group_id' => 2,
            'language' => 'en',
            'email_confirmed' => true,
        ])->bills()->create([
            'currency_id' => 1,
            'uuid' => md5(uniqid()),
            'amount' => '0.01'
        ]);

        User::create([
            'name' => 'third',
            'email' => 'third@example.com',
            'password' => 12341234,
            'group_id' => 2,
            'language' => 'ru',
            'email_confirmed' => true,
        ])->bills()->create([
            'currency_id' => 2,
            'uuid' => md5(uniqid()),
            'amount' => '10'
        ]);
    }
}
