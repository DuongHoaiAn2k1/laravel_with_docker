<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\QuoteController::class, 'index']);


Route::get('/redis-set', function () {
    Redis::set('key', 'Hello, Redis!');
    Redis::expire('key', 30);


    return response()->json([
        'status' => 'success',
        'value' => 'Set key Successfully!'
    ]);
});

Route::get('/redis-get', function () {
    $value = Redis::get('key');
    return response()->json([
        'key' => 'key',
        'value' => $value
    ]);
});