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
