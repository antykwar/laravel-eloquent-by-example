<?php

use App\Models\Cat;
use App\Models\Dog;
use App\Models\Dog\DogAccessors;
use App\Models\Dog\DogWithAInName;
use App\Models\User;
use App\Transformers\DogTransformer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dogs', function () {
    return response()
        ->json(
            Dog::all()
                ->transformWith(new DogTransformer())
        );
});

Route::prefix('queries')->group(function() {
    Route::get('/listener', function() {
        DB::listen(function($event) {
            dump($event->sql);
            dump($event->bindings);
        });

        Dog::find(1);
    });

    Route::get('/users_with_dogs_not_clean_way', function() {
        return response()->json(
            User::join('dogs', 'user_id', '=', 'users.id')
                ->get()
                ->pluck('email')
                ->unique()
        );
    });

    Route::get('/users_with_dogs_clean_way', function() {
        return response()->json(
            User::has('dogs')
                ->get()
                ->pluck('email')
        );
    });

    Route::get('/users_with_dogs_count', function() {
        return response()->json(
            User::withCount('dogs')
                ->get()
        );
    });

    Route::get('/users_with_dogs_count_condition', function() {
        return response()->json(
            User::withCount(['dogs' => function($query) {
                $query->where('name','like', 'M%');
            }])->get()
        );
    });
});

Route::prefix('cats')->group(function() {
    Route::get('/modify_name', function() {
        $cat = Cat::first();

        Cat::where('info->name', $cat->info['name'])
            ->update(['info->name' => 'Modified']);

        return response()->json(
            Cat::where('info->name', 'Modified')->first()
        );
    });

    Route::get('/ordered_list', function() {
        return response()->json(
            Cat::query()
                ->orderBy('info->name')->get()
        );
    });
});

Route::prefix('dogs')->group(function() {
    Route::get('/old_dogs', function () {
        return response()->json(
            (new Dog)->oldDogsList()
                ->get()
                ->transformWith(new DogTransformer())
        );
    });

    Route::get('/using_global_scope', function () {
        return response()->json(
            DogWithAInName::all()
        );
    });

    Route::get('/without_global_scope', function () {
        return response()->json(
            DogWithAInName::withoutGlobalScope('nameFilter')->get()
        );
    });

    Route::get('/soft_deleted', function () {
        Dog::find(4)?->delete();
        return response()->json(
            Dog::onlyTrashed()->find(4)
        );
    });

    Route::get('/accessor', function () {
        return response()->json(
            [
                DogAccessors::find(1)->name,
                DogAccessors::find(1)->getAttributes()['name'],
                DogAccessors::find(1)->idName
            ]
        );
    });

    Route::get('/carbon_date', function () {
        return response()->json(
            Carbon::now()->diffInHours(DogAccessors::find(1)->created_at)
        );
    });

    Route::get('/mutator', function () {
        $dog = DogAccessors::find(1);
        $originalName = $dog->name;
        $dog->name = $originalName;
        $dog->save();
        $newName = DogAccessors::find(1)->name;

        return response()->json(
            [
                $originalName,
                $newName
            ]
        );
    });

    Route::get('/{minAge}', function ($minAge) {
        return response()->json(
            Dog::ageGreaterThan((int)$minAge)
                ->get()
                ->transformWith(new DogTransformer())
        );
    });

    Route::get('/dogs_age_groups/{ageGroup}', function ($ageGroup) {
        return response()->json(
            Dog::select('name', 'age')->when(
                $ageGroup === 'old',
                function($query) {
                    $query->where('age', '>', '8');
                },
                function($query) {
                    $query->where('age', '<', '6');
                }
            )->get()
        );
    });
});
