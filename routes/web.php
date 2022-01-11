<?php

use App\Models\Dog;
use App\Models\Dog\DogWithAInName;
use App\Transformers\DogTransformer;
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

Route::get('/dogs/old_dogs', function () {
    return response()->json(
        (new Dog)->oldDogsList()
            ->get()
            ->transformWith(new DogTransformer())
    );
});

Route::get('/dogs/using_global_scope', function () {
    return response()->json(
        DogWithAInName::all()
    );
});

Route::get('/dogs/without_global_scope', function () {
    return response()->json(
        DogWithAInName::withoutGlobalScope('nameFilter')->get()
    );
});

Route::get('/dogs/soft_deleted', function () {
    Dog::find(4)?->delete();
    return response()->json(
        Dog::onlyTrashed()->find(4)
    );
});

Route::get('/dogs/{minAge}', function ($minAge) {
    return response()->json(
        Dog::ageGreaterThan((int)$minAge)
            ->get()
            ->transformWith(new DogTransformer())
    );
});

