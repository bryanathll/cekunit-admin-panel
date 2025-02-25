<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\InputDataController;
use App\Http\Controllers\User\cekunitController;
use App\Http\Controllers\User\usersController;
use App\Http\Controllers\ProfileController;
use App\Exports\CekUnitExport;
use App\Exports\input_user_export;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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

 Route::redirect('/', '/login')->name('login');


Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [cekunitController::class, 'index'])->name('dashboard');

    // Route::post('/cekunit/sort', [CekUnitController::class, 'sort'])->name('cekunit.sort');
    
    Route::get('/input-user', [cekunitController::class, 'input_user'])->name('input.user');
    Route::get('/users', [usersController::class, 'users'])->name('users');
    // Route::post('/input_user/sort', [CekUnitController::class, 'sort_input_user'])->name('input_user.sort');

    Route::get('/input-data', [InputDataController::class, 'create'])->name('input.data');
    Route::post('/input-data', [InputDataController::class, 'store'])->name('input.data-nasabah');

    Route::get('/cekunit/export', function (Request $request) {

        $format = $request->query('format', 'csv');
        $sortColumn = $request->query('sort', 'no');
        $sortDirection = $request->query('direction', 'asc');
    
        $filename = 'cekunit_' . date('Ymd_His') . '.' . $format;
    
        return Excel::download(
            new CekUnitExport($sortColumn, $sortDirection),
            $filename,
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    })->name('cekunit.export');

    Route::get('/input_user/export', function (Request $request) {

        $format = $request->query('format', 'csv');
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');
    
        $filename = 'input_user_' . date('Ymd_His') . '.' . $format;
    
        return Excel::download(
            new input_user_export($sortColumn, $sortDirection),
            $filename,
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    })->name('input_user.export');
    
    Route::resource('input_user', cekunitController::class)->except(['show']);

    Route::post('/input_user/insert', [cekunitController::class, 'import'])->name('input.data.import');

    Route::delete('/delete-all', [cekunitController::class, 'deleteAll'])->name('cekunit.deleteAll');

    Route::get('/cekunit/get-unique-values', [CekUnitController::class, 'getUniqueValues'])->name('cekunit.getUniqueValues');

    // Route untuk menghapus data berdasarkan kolom dan nilai
    Route::post('/cekunit/delete-by-category', [CekUnitController::class, 'deleteByCategory'])->name('cekunit.deleteByCategory');


});

Route::resource('cekunit', cekunitController::class)->parameters([
    'cekunit' => 'no'
]);

Route::resource('users', usersController::class)->parameters([
    'users' => 'nomor'
]);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';