<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoutineController;

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

Route::get('/register', function () {
    abort(404);
});

Route::get('/forgot-password', function () {
    abort(404);
});

Route::get('/', function () {
    return view('dashboard');
});

Route::middleware('can:user-higher')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('task.store');
    Route::get('/tasks/{task}', [TaskController::class, 'edit'])->name('task.edit');
    Route::post('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/procedure/{procedure}', [ProcedureController::class, 'edit'])->name('procedure.edit');
    Route::post('/procedure/{procedure}', [ProcedureController::class, 'update'])->name('procedure.update');
    Route::get('/procedure/store/{id}', [ProcedureController::class, 'create'])->name('procedure.create');
    Route::post('/procedure/store/{id}', [ProcedureController::class, 'store'])->name('procedure.store');

    Route::get('/task/{id1}/routine/{id2}', [RoutineController::class, 'index'])->name('procedure.routine');
    Route::post('/task/{id}/routine', [RoutineController::class, 'update'])->name('procedure.routine_update');
    Route::delete('/task/{id}/routine', [RoutineController::class, 'destroy'])->name('procedure.routine_delete');

    Route::get('/manual', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/manual/store', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/manual/store', [DocumentController::class, 'store'])->name('document.store');
    Route::get('/manual/{document}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('/manual/{document}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('/manual/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/manual/download/{document}', [DocumentController::class, 'file_download'])->name('document.file_download');
});

Route::get('/task_list', [DashboardController::class, 'tasks'])->name('dashboard.tasks');
Route::get('/product/{id}', [DashboardController::class, 'task_details'])->name('dashboard.task_details');
Route::get('/faq_manual', [DashboardController::class, 'documents'])->name('dashboard.documents');
Route::get('/faq_manual/{id}', [DashboardController::class, 'documents_details'])->name('dashboard.documents_details');
Route::get('/product/{id1}/{id2}', [DashboardController::class, 'procedures'])->name('dashboard.procedures');
Route::get('/search', [DashboardController::class, 'search'])->name('dashboard.search');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
