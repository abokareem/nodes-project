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
        factory(\App\Currency::class, 20)->create();
    }
}
