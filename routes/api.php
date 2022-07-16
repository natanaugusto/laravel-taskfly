<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->name('task.')->group(callback: function () {
    Route::get(uri: '/', action: [TaskController::class, 'all'])->name('all');
    Route::get(uri: '/{task}', action: [TaskController::class, 'view'])->name('view');
    Route::post(uri: '/', action: [TaskController::class, 'store'])->name('store');
});
