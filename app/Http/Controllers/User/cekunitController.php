<?php

namespace App\Http\Controllers\User;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cekunit;
use App\Models\input_user;
use App\Models\users;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use App\Exports\input_user_export;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Response;
// use Illuminate\Support\Facades\StreamedResponse;



class cekunitController extends Controller
{

    public function streamExport(Request $request)
    {
        $search = $request->query('search','');
        $sort = $request->query('sort','id');
        $direction = $request->query('direction','asc');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $filename = 'input_user_' . now()->format('Ymd_His') . '.csv';
    
        // Query builder with sorting
        $query = input_user::orderBy($sort, $direction);

        if($sort == 'created_at'){
            if($startDate && $endDate){
                $query->whereRaw('DATE(created_at) BETWEEN ? AND ?',[$startDate, $endDate]);
            }
        }

        $query->orderBy($sort, $direction);
    
        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
    
            // UTF-8 BOM for Excel
            fwrite($handle, "\xEF\xBB\xBF");
    
            // CSV Headers
            fputcsv($handle, [
                'no', 
                'created_at', 
                'userID', 
                'nopol', 
                'lokasi', 
                'ForN', 
                'nama',
                'nama_nasabah',
                'kategori',
                'no_perjanjian'
            ]);
    
            // Chunked data processing
            $query->chunk(5000, function ($users) use ($handle) {
                static $no = 1;
    
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $no++,
                        $user->created_at,
                        $user->userID,
                        $user->nopol,
                        $user->lokasi,
                        $user->ForN,
                        $user->nama,
                        $user->nama_nasabah,
                        $user->kategori,
                        $user->no_perjanjian
                    ]);
                }
    
                flush();
                if (ob_get_level() > 0) {
                    ob_flush();
                }
            });
    
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }


// ============================================ start Controller index ================================================ 
    // Method untuk menangani kedua kasus: tampilan utama dan permintaan AJAX
    public function index(Request $request) {
        // Ambil parameter dari request
        $search = $request->query('search', ''); // Parameter pencarian
        $sort = $request->query('sort', 'no'); // Parameter sorting (default: 'no')
        $direction = $request->query('direction', 'asc'); // Parameter arah sorting (default: 'asc')
    
        // Query data dengan filter pencarian
        $cekunit = cekunit::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER(no_perjanjian) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(nama_nasabah) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(nopol) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(no_rangka) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(no_mesin) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(merk) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(type) LIKE ?', ['%' . strtolower($search) . '%']);
        })
        ->orderBy($sort, $direction) // Sorting
        ->paginate(20) // Pagination
        ->appends([
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction
        ]);
    
        // Jika request AJAX, kembalikan view pagination saja
        if ($request->ajax()) {
            return view('pages.user.pagination_table', compact('cekunit', 'sort', 'direction', 'search'))->render();
        }
    
        // Jika bukan AJAX, kembalikan view lengkap
        return view('pages.user.dashboard', compact('cekunit', 'sort', 'direction', 'search'));
    }
// ================================================ end Controller index ==============================================




// ============================================ start Controller input_user =========================================== 
public function input_user(Request $request) {
        $search = $request->query('search', '');
        $sort = $request->query('sort','id');
        $direction = $request->query('direction', 'asc');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = input_user::query();

        if($sort == 'created_at'){
            if($startDate && $endDate){
                $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }
        }

        $input_user = $query->when($search, function ($query, $search){
            return $query->whereRaw('LOWER(nopol) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(no_perjanjian) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(nama_nasabah) LIKE ?', ['%' . strtolower($search) . '%']);
        })
        ->orderBy($sort, $direction)
        ->paginate(20)
        ->appends([
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if ($request->ajax()){
            return view('pages.user.input_user', compact('input_user', 'sort', 'direction'))->render();
        }

        return view('pages.user.input_user_table', compact('input_user', 'sort', 'direction'));
    }
// ============================================== end Controller input_user ===========================================
    


// ============================================= start Controller delete ==============================================
public function destroy($no)
{
    $unit = cekunit::find($no);
    $unit->delete();
    return redirect()->route('dashboard')->with('success', 'Hapus Data Berhasil');
}
// ============================================== end Controller delete ===============================================



// ============================================= Start Controller update =============================================
    public function update(Request $request, $no)
        {
            $unit = cekunit::find($no);
            $unit->update($request->all());
            return redirect()->route('dashboard')->with('success', 'Edit Data Berhasil');
        }
// ============================================== end Controller update ==============================================




// ========================================= start Controller sort_input_user ======================================== 
    public function sort_input_user(Request $request)
        {
            
            // ambil parameter dari request
            $sort = $request->input('sort');
            $direction = $request->input('direction');
            
            // Query data dengan sorting
            $input_user = input_user::orderBy($sort, $direction)->paginate(20);
            
            
            // kirim data dalam format JSON
            return response()->json([
                'data' => $input_user->items(),
                'pagination' => $input_user->appends(['sort'=>$sort, 'direction'=>$direction])->links()->toHtml()
            ]);
        }
// ========================================== end Controller sort_input_user ========================================= 



// ============================================== start Controller show ============================================== 
    public function show($no)
        {
            $unit = cekunit::find($no);
            return view('user.cekunit.show', compact('unit'));
        }
// =============================================== end Controller show =============================================== 



// ============================================= start Controller import =============================================
public function import(Request $request) {
    set_time_limit(0);

    // Validasi file CSV
    $validator = Validator::make($request->all(), [
        'csv_file' => 'required|mimes:csv,txt|max:5048'
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Ambil file CSV
    $file = $request->file('csv_file');
    if (!$file || !$file->isValid()) {
        return redirect()->back()->withErrors(['csv_file' => 'File tidak ditemukan atau rusak!'])->withInput();
    }

    // Pastikan encoding file adalah UTF-8
    $filePath = $file->getPathname();
    $convertedFilePath = storage_path('app/temp_converted.csv');
    file_put_contents($convertedFilePath, mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'auto'));

    // Gunakan League CSV untuk membaca file yang telah dikonversi ke UTF-8
    $csv = Reader::createFromPath($convertedFilePath, 'r');
    $csv->setHeaderOffset(0); // Baris pertama sebagai header

    // Deteksi delimiter secara otomatis
    $csv->setDelimiter($this->detectDelimiter($convertedFilePath));

    $records = $csv->getRecords();
    $dataToInsert = [];

    // Pastikan database menerima UTF-8
    // DB::statement("SET NAMES utf8mb4");

    // Proses setiap baris data
    foreach ($records as $row) {
        // Bersihkan karakter tidak valid sebelum validasi
        $cleanRow = array_map(function($value) {
            return trim(mb_convert_encoding($value, 'UTF-8', 'auto'));
        }, $row);

        $validator = Validator::make($cleanRow, [
            'no_perjanjian' => 'nullable|string',
            'nama_nasabah' => 'nullable|string',
            'nopol' => 'nullable|string',
            'coll' => 'nullable|string',
            'pic' => 'nullable|string',
            'kategori' => 'nullable|string',
            'jto' => 'nullable|integer',
            'no_rangka' => 'nullable|string',
            'no_mesin' => 'nullable|string',
            'merk' => 'nullable|string',
            'type' => 'nullable|string',
            'warna' => 'nullable|string',
            'status' => 'nullable|string',
            'actual_penyelesaian' => 'nullable|string',
            'angsuran' => 'nullable|integer',
            'tenor' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            continue; // Lewati baris yang gagal validasi
        }

        $dataToInsert[] = [
            'no_perjanjian' => $cleanRow['no_perjanjian'] ?? null,
            'nama_nasabah' => $cleanRow['nama_nasabah'] ?? null,
            'nopol' => $cleanRow['nopol'] ?? null,
            'coll' => $cleanRow['coll'] ?? null,
            'pic' => $cleanRow['pic'] ?? null,
            'kategori' => $cleanRow['kategori'] ?? null,
            'jto' => isset($cleanRow['jto']) ? (int) $cleanRow['jto'] : null,
            'no_rangka' => $cleanRow['no_rangka'] ?? null,
            'no_mesin' => $cleanRow['no_mesin'] ?? null,
            'merk' => $cleanRow['merk'] ?? null,
            'type' => $cleanRow['type'] ?? null,
            'warna' => $cleanRow['warna'] ?? null,
            'status' => $cleanRow['status'] ?? null,
            'actual_penyelesaian' => $cleanRow['actual_penyelesaian'] ?? null,
            'angsuran' => isset($cleanRow['angsuran']) ? (int) $cleanRow['angsuran'] : null,
            'tenor' => isset($cleanRow['tenor']) ? (int) $cleanRow['tenor'] : null,
        ];
    }

    // Insert data ke database dalam batch
    foreach (array_chunk($dataToInsert, 2000) as $batch) {
        DB::table('cekunit')->insert($batch);
    }

    return redirect()->back()->with('success', 'Data berhasil diinsert!');
}

// Fungsi untuk mendeteksi delimiter secara otomatis
private function detectDelimiter($filePath) {
    $delimiters = [',', ';', "\t"];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!$lines || count($lines) < 2) {
        return ","; // Default fallback
    }

    $delimiterCount = [];
    foreach ($delimiters as $delimiter) {
        $delimiterCount[$delimiter] = substr_count($lines[0], $delimiter);
    }

    return array_search(max($delimiterCount), $delimiterCount);
}

// ============================================== end Controller import ============================================== 



// ============================================ start Controller deleteAll =========================================== 
    public function deleteAll()
    {
        // Hapus semua data berdasarkan kolom 'no'
        cekunit::query()->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Semua data berhasil dihapus.');
    }
// ============================================= end Controller deleteAll ============================================ 
    
    
    
// ============================================= start Controller delete by category ================================= 
    // Method untuk mengambil data unik berdasarkan kolom
    public function getUniqueValues(Request $request)
    {
        $request->validate([
            'column' => 'required|string|in:kategori,status,actual_penyelesaian', // Sesuaikan dengan kolom yang ada
        ]);

        $column = $request->input('column');

        // Ambil data unik dari kolom
        $uniqueValues = cekunit::select($column)
            ->whereNotNull($column)
            ->orderBy($column)
            ->distinct()
            ->pluck($column);

        return response()->json($uniqueValues);
    }

    // Method untuk menghapus data berdasarkan kolom dan nilai
    public function deleteByCategory(Request $request)
    {
        $request->validate([
            'column' => 'required|string|in:kategori,status,actual_penyelesaian', // Sesuaikan dengan kolom yang ada
            'value' => 'required|string',
        ]);

        $column = $request->input('column');
        $value = $request->input('value');
        
        if($value == 'null'){
            $deleted = cekunit::whereNull($column)->delete();
        }else{
            $deleted = cekunit::where($column,$value)->delete();
        }

        if($deleted){
            return response()->json(['success' => true, 'message' => 'Data Berhasil Dihapus']);
        }else{
            return response()->json(['success' => false, 'message' => 'Gagal Menghapus Data']);
        }
    }
// ============================================== end Controller delete by category ==================================
    
}