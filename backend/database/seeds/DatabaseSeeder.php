<?php

use Illuminate\Database\Seeder;
use App\UserGroup;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(UserGroup::class)->create(['name' => 'admin']);
        app(UserGroup::class)->create(['name' => 'user']);
        \App\Commission::create([
            'type' => \App\Commission::FOR_SINGLE_NODE,
            'percent' => '9'
        ]);
        \App\Commission::create([
            'type' => \App\Commission::FOR_PARTY_NODE,
            'percent' => '12'
        ]);
        $this->call([
            MasternodeSeeder::class,
            UserSeeder::class
        ]);
        \App\Commission::create([
            'type' => \App\Commission::REPLENISH,
            'percent' => '20'
        ]);
    }
}
