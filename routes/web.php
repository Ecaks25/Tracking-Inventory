<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\TtpbController;
use App\Http\Controllers\BpgController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MonitoringController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');

    Route::resource('bpg', BpgController::class)->only(['edit', 'update', 'destroy']);
    Route::resource('ttpb', TtpbController::class)->only(['edit', 'update', 'destroy']);

    $roles = config('roles');

    foreach ($roles as $role) {
        Route::get("{$role}/stock", [RoleController::class, 'stock'])->defaults('role', $role)->name("{$role}.stock");
        Route::get("{$role}/stock/create", [RoleController::class, 'stockCreate'])->defaults('role', $role)->name("{$role}.stock.create");
        if ($role === 'gudang') {
            Route::post("{$role}/stock", [BpgController::class, 'store'])->name("{$role}.stock.store");
        }

        Route::get("{$role}/ttpb", [RoleController::class, 'ttpb'])->defaults('role', $role)->name("{$role}.ttpb");
        Route::get("{$role}/ttpb/create", [RoleController::class, 'ttpbCreate'])->defaults('role', $role)->name("{$role}.ttpb.create");
        Route::post("{$role}/ttpb", [TtpbController::class, 'store'])->name("{$role}.ttpb.store");
        Route::get("{$role}/ttpb/preview", [RoleController::class, 'ttpbPreview'])->defaults('role', $role)->name("{$role}.ttpb.preview");

        Route::get("{$role}/monitoring", [RoleController::class, 'monitoring'])->defaults('role', $role)->name("{$role}.monitoring");
    }

    Route::view('mixing/barang-jadi', 'mixing.barang-jadi', ['role' => 'mixing'])
        ->name('mixing.barang_jadi');
    Route::view('grinding/barang-jadi', 'grinding.barang-jadi', ['role' => 'grinding'])
        ->name('grinding.barang_jadi');

    Route::get('monitoring/stock', [MonitoringController::class, 'stock'])
        ->name('monitoring.stock');

    Route::get('gudang/api/bpg/{lotNumber}', [RoleController::class, 'gudangBpg'])
        ->name('gudang.bpg.show');
    Route::get('{role}/api/ttpb/{lotNumber}', [RoleController::class, 'roleTtpbShow'])
        ->whereIn('role', $roles)
        ->name('role.ttpb.show');
});

require __DIR__ . '/auth.php';

