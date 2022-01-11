<?php

namespace Database\Seeders;

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

        $this->call([
            DogsTableSeeder::class,
            DogsTableExactDataSeeder::class,
        ]);
    }
}
