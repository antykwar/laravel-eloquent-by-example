<?php

namespace Database\Seeders;

use App\Models\Dog;
use Illuminate\Database\Seeder;

class DogsTableExactDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect([
            ['name' => 'Ann', 'age' => 5],
            ['name' => 'Jane', 'age' => 2],
            ['name' => 'John', 'age' => 8],
            ['name' => 'Bob', 'age' => 11],
            ['name' => 'Angela', 'age' => 3],
        ]);

        $data->each(function($dog) {
            Dog::factory()
                ->create($dog);
        });
    }
}
