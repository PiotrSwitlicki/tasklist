<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DashboardController;

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

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();


Route::middleware('auth')->group(function () {


    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

        Route::group(['middleware' => ['verified']], function() {
            /**
             * Dashboard Routes
             */
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
            Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
            Route::get('/tasks/show/{id}', [TaskController::class, 'show'])->name('tasks.show');
            Route::get('/tasks/filter', [TaskController::class, 'filter'])->name('tasks.filter');

            // Create task
            Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');

            // Edit task
            Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
            Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
            Route::put('/tasks/{id}/updatestatus', [TaskController::class, 'updateStatus'])->name('tasks.updatestatus');

            // Delete task
            Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
            Route::get('/home', [TaskController::class, 'index'])->name('home');
    });
});


