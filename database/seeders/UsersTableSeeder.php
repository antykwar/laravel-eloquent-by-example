<?php

namespace Database\Seeders;

use App\Models\Dog;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function($user) {
                $freeDogs = Dog::whereNull('user_id')->take(2)->get();

                if ($freeDogs->count()) {
                    $user->dogs()->saveMany($freeDogs);
                }
            });
    }
}
