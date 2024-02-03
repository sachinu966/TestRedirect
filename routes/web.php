<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/store-redirection-data', [HomeController::class, 'storeRedirectionData'])->name('store-redirection-data');
Route::get('/generate-redirection-script', [HomeController::class,'generateRedirectionScript']);
Route::get('/handle-redirection', [HomeController::class,'handleRedirection']);
