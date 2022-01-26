<?php

use App\Models\Cat;
use App\Models\Dog;
use App\Models\Dog\DogAccessors;
use App\Models\Dog\DogWithAInName;
use App\Transformers\DogTransformer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

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
