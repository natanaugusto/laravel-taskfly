<?php

use App\Models\Task;
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

Route::middleware('auth')->group(callback:function () {
    Route::get(uri:'/tasks', action:static fn () => view('tasks'))->name('tasks');
    Route::get(uri:'/dashboard', action:static fn () => view('dashboard'))->name('dashboard');
});

require __DIR__ . '/auth.php';
