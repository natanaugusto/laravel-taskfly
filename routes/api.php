<?php

use App\Http\Controllers\TaskController;

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
Route::middleware('auth:sanctum')->group(callback:function () {
    Route::get(uri:'/user', action:fn (Request $request) => $request->user());
    Route::prefix('task')->name('task.')->group(callback:function () {
        Route::get(uri:'/', action:[TaskController::class, 'all'])->name('all');
        Route::get(uri:'/{task}', action:[TaskController::class, 'view'])->name('view');
        Route::post(uri:'/', action:[TaskController::class, 'store'])->name('store');
        Route::put(uri:'/{task}', action:[TaskController::class, 'update'])->name('update');
        Route::put(uri:'/{task}/relate', action:[TaskController::class, 'relate'])->name('relate');
        Route::delete(uri:'/{task}', action:[TaskController::class, 'delete'])->name('delete');
    });
});
