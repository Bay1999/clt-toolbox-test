<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CltLayupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('supplier/data', [SupplierController::class, 'getData'])->name('supplier.data');
    Route::get('supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
    Route::resource('supplier', SupplierController::class);

    Route::get('clt-layup/data', [CltLayupController::class, 'getData'])->name('clt-layup.data');
    Route::patch('clt-layup/{id}/status', [CltLayupController::class, 'updateStatus'])->name('clt-layup.status');
    Route::post('clt-layup/{id}/restore', [CltLayupController::class, 'restore'])->name('clt-layup.restore');
    Route::resource('clt-layup', CltLayupController::class);
});

require __DIR__ . '/auth.php';
