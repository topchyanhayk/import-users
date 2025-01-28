<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportUsersController;
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



Route::get('/import/{importId}/status', [ImportUsersController::class, 'getStatus']);
Route::post('/import/run', [ImportUsersController::class, 'run']);
Route::get('/import/last/info', [ImportUsersController::class, 'getLastImport']);
