<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();
        //\App\Models\Ship::factory(10000)->create();
        //\App\Models\City::factory(1000)->create();
        \App\Models\Offer::factory(2000)->create();

      /*  $this->call([
            CountrySeeder::class,
        ]);*/

    }
}

