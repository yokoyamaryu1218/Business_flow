<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\ApprovalsController;

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
    Route::get('/manual/{document}/history/{id}', [DocumentController::class, 'history_download'])->name('document.history_download');

    Route::post('/manual/{document}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('/manual/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');

    Route::get('/approval', [ApprovalsController::class, 'index'])->name('approval.index');
    Route::get('/approved', [ApprovalsController::class, 'approved'])->name('approval.approved');

    Route::get('/approval/{documents}', [ApprovalsController::class, 'document_edit'])->name('approval.document_edit');
    Route::post('/approval/{documents}', [ApprovalsController::class, 'document_update'])->name('approval.document_update');

    Route::get('/approval/routine/{routines}', [ApprovalsController::class, 'routine_edit'])->name('approval.routine_edit');
    Route::post('/approval/routine/{routines}', [ApprovalsController::class, 'routine_update'])->name('approval.routine_update');

    Route::get('/approval/procedure/{procedures}', [ApprovalsController::class, 'procedure_edit'])->name('approval.procedure_edit');
    Route::post('/approval/procedure/{procedures}', [ApprovalsController::class, 'procedure_update'])->name('approval.procedure_update');
});

Route::middleware('can:admin')->group(function () {
    Route::get('/employee', [UsersController::class, 'index'])->name('user.index');
    Route::get('/employee/store', [UsersController::class, 'create'])->name('user.create');
    Route::post('/employee/store', [UsersController::class, 'store'])->name('user.store');
    Route::get('/employee/{id}', [UsersController::class, 'edit'])->name('user.edit');
    Route::post('/employee/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::post('/employee/password/{id}', [UsersController::class, 'password_update'])->name('user.password_update');
    Route::delete('/employee/{id}', [UsersController::class, 'destroy'])->name('user.destroy');
});

Route::get('/task_list', [DashboardController::class, 'tasks'])->name('dashboard.tasks');
Route::get('/task_list/search', [DashboardController::class, 'tasks_search'])->name('dashboard.tasks_search');
Route::get('/product/{id}', [DashboardController::class, 'task_details'])->name('dashboard.task_details');
Route::get('/product/{id}/search', [DashboardController::class, 'procedures_search'])->name('dashboard.procedures_search');
Route::get('/faq_manual', [DashboardController::class, 'documents'])->name('dashboard.documents');
Route::get('/faq_manual/search', [DashboardController::class, 'documents_search'])->name('dashboard.documents_search');
Route::get('/faq_manual/{id}', [DashboardController::class, 'documents_details'])->name('dashboard.documents_details');
Route::get('/product/{id1}/{id2}', [DashboardController::class, 'procedures'])->name('dashboard.procedures');
Route::get('/search', [DashboardController::class, 'search'])->name('dashboard.search');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
