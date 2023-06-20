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

    Route::get('/task', [TaskController::class, 'index'])->name('task.index');

    Route::get('/task/task_search', [TaskController::class, 'search'])->name('task.search');
    Route::get('/task/procedure_search', [ProcedureController::class, 'search'])->name('task.procedure.search');

    Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/task/create', [TaskController::class, 'store'])->name('task.store');

    Route::get('/task/{task}', [TaskController::class, 'edit'])->name('task.edit');
    Route::post('/task/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/task/{id}/search', [ProcedureController::class, 'procedure_search'])->name('task.procedure.procedure_search');

    Route::get('/task/procedure/create', [ProcedureController::class, 'procedure_create'])->name('task.procedure.procedure_create');
    Route::post('/task/procedure/create', [ProcedureController::class, 'procedure_store'])->name('task.procedure.procedure_store');

    Route::get('/task/{id1}/procedure/{id2}', [ProcedureController::class, 'edit'])->name('task.procedure.edit');
    Route::post('/task/{id1}/procedure/{id2}', [ProcedureController::class, 'update'])->name('task.procedure.update');
    Route::delete('/task/{id1}/procedure/{id2}', [ProcedureController::class, 'destroy'])->name('task.procedure.destroy');

    Route::get('/procedure/store/{id}', [ProcedureController::class, 'create'])->name('task.procedure.create');
    Route::post('/procedure/store/{id}', [ProcedureController::class, 'store'])->name('task.procedure.store');

    Route::get('/task/{id}/routine/create', [RoutineController::class, 'create'])->name('task.procedure.routine_create');
    Route::post('/task/{id}/routine/create', [RoutineController::class, 'store'])->name('task.procedure.routine_store');

    Route::get('/task/{id1}/routine/{id2}', [RoutineController::class, 'index'])->name('task.procedure.routine_edit');
    Route::post('/task/{id}/routine', [RoutineController::class, 'update'])->name('task.procedure.routine_update');
    
    Route::delete('/task/{id}/routine', [RoutineController::class, 'destroy'])->name('task.procedure.routine_delete');

    Route::get('/manual', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/manual/search', [DocumentController::class, 'search'])->name('document.search');
    Route::get('/manual/store', [DocumentController::class, 'create'])->name('document.create');
    Route::get('/manual/file', [DocumentController::class, 'file'])->name('document.file');
    Route::post('/manual/store', [DocumentController::class, 'store'])->name('document.store');
    Route::post('/manual/file', [DocumentController::class, 'file_store'])->name('document.file_store');
    Route::get('/manual/download/{document}', [DocumentController::class, 'file_download'])->name('document.file_download');
    Route::get('/manual/download-all-documents', [DocumentController::class, 'all_download'])->name('document.all_download');
    Route::get('/manual/{document}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('/manual/{document}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('/manual/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
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
