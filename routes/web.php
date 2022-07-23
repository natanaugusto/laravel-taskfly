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

Route::get('/', static function () {
    return view('welcome');
});

Route::middleware('auth')->group(static function () {
    Route::get('/dashboard', static fn() => view('dashboard'))->name('dashboard');
    Route::get(
        '/tasks',
        static fn() => view('tasks', ['tasks' => Task::paginate()])
    )->name('tasks');
});

require __DIR__.'/auth.php';
