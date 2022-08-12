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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', static fn() => view('dashboard'))->name('dashboard');
    Route::prefix('/tasks')->name('tasks.')->group(static function () {
        Route::get(
            '/', static fn() => view('tasks.index', ['tasks' => Task::paginate()])
        )->name('index');

        Route::get(
            '/create', static fn() => view('tasks.form')
        )->name('create');

        Route::get(
            '/{task}/edit', static fn(Task $task) => view('tasks.form', ['task' => $task])
        )->name('edit');

    });
});

require __DIR__ . '/auth.php';
