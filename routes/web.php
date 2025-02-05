<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\InputDataController;
use App\Http\Controllers\User\cekunitController;
use App\Http\Controllers\ProfileController;
use App\Exports\CekUnitExport;
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
    Route::post('/cekunit/sort', [CekUnitController::class, 'sort'])->name('cekunit.sort');
    Route::get('/input-data', [InputDataController::class, 'create'])->name('input.data');
    Route::post('/input-data', [InputDataController::class, 'store'])->name('input.data-nasabah');

    Route::get('/cekunit/export', function (Request $request) {

        $allowedColumns = [
            'nomor', 'no_perjanjian', 'nama_nasabah', 'nopol', 
            'coll', 'pic', 'kategori', 'jto', 'no_rangka', 
            'no_mesin', 'merk', 'type', 'warna', 'status'
        ];

        $format = $request->query('format', 'excel');
        $sortColumn = $request->query('sort', 'nomor');
        $sortDirection = $request->query('direction', 'asc');
    
        $filename = 'cekunit_' . date('Ymd_His') . '.' . $format;
    
        return Excel::download(
            new CekUnitExport($sortColumn, $sortDirection),
            $filename,
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    })->name('cekunit.export');
    
    Route::resource('cekunit', cekunitController::class)->except(['show']);
});

Route::resource('cekunit', cekunitController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
