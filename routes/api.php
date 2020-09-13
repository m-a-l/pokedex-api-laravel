<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pokemons', 'PokemonController@index');
Route::get('/pokemon/{id}', 'PokemonController@read');
Route::post('/pokemons/new', 'PokemonController@create');
Route::delete('/pokemon/{id}', 'PokemonController@delete');
Route::put('/pokemon/{id}', 'PokemonController@update');
