<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\DocumentController;

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

Route::middleware('can:user-higher')->group(function () {
    Route::get('/manual', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/manual/store', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/manual/store', [DocumentController::class, 'store'])->name('document.store');
    Route::get('/manual/{document}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('/manual/{document}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('/manual/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
