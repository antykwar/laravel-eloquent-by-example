<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Dog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Dog::truncate();
        Cat::truncate();

        $this->call([
            DogsTableSeeder::class,
            UsersTableSeeder::class,
            CatsTableSeeder::class,
        ]);
    }
}
