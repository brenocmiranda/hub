<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiLeadsCtrl;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Leads
Route::group(['prefix' => 'leads'], function () {
    Route::get('/', [ApiLeadsCtrl::class, 'index'])->middleware(['auth:sanctum']);
    Route::post('/{company}', [ApiLeadsCtrl::class, 'store'])->middleware(['auth:sanctum']);
});