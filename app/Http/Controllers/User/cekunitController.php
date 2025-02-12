<?php

namespace App\Http\Controllers\User;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cekunit;
use App\Models\input_user;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class cekunitController extends Controller
{



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
// ================================================ end Controller index ==================================================



// ============================================ start Controller input_user ================================================ 
    public function input_user(Request $request) {
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');

        $input_user = input_user::orderBy($sort, $direction)
            ->paginate(20)
            ->appends([
                'sort' => $sort,
                'direction' => $direction
            ]);

        if ($request->ajax()) {
            return view('pages.user.input_user', compact('input_user', 'sort', 'direction'))->render();
        }

        return view('pages.user.input_user_table', compact('input_user', 'sort', 'direction'));
    }
// ============================================== end Controller input_user ================================================



// ============================================== start Controller edit ===============================================
    public function edit($no)
        {
            $unit = cekunit::find($no);
            return view('cekunit.edit', compact('unit'));
        }
// =============================================== end Controller edit ================================================ 


    
// ============================================= start Controller delete =============================================
    public function destroy($no)
        {
            $unit = cekunit::find($no);
            $unit->delete();
            return redirect()->route('dashboard')->with('success', 'Hapus Data Berhasil');
        }
// ============================================== end Controller delete ==============================================



// ============================================= Start Controller update =============================================
    public function update(Request $request, $no)
        {
            $unit = cekunit::find($no);
            $unit->update($request->all());
            return redirect()->route('dashboard')->with('success', 'Edit Data Berhasil');
        }
// ============================================== end Controller update ==============================================



// ============================================== Start Controller sort ==============================================
    public function sort(Request $request)
        {
            
            // ambil parameter dari request
            $sort = $request->input('sort');
            $direction = $request->input('direction');
            
            // Query data dengan sorting
            $cekunit = cekunit::orderBy($sort, $direction)->paginate(20);
            
            
            // kirim data dalam format JSON
            return response()->json([
                'data' => $cekunit->items(),
                'pagination' => $cekunit->appends(['sort'=>$sort, 'direction'=>$direction])->links()->toHtml()
            ]);
        }
// =============================================== end Controller sort ===============================================



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
    
        // Baca file CSV
        $csvData = array_map('str_getcsv', file($file->getPathname()));
    
        // Hapus header
        $header = array_shift($csvData);
        if (!$header) {
            return redirect()->back()->withErrors(['csv_file' => 'Format file tidak valid'])->withInput();
        }
    
        $dataToInsert = [];
    
        // Proses setiap baris data
        foreach ($csvData as $row) {
            // Lewati baris jika jumlah kolom tidak sesuai
            if (count($row) !== count($header)) {
                continue;
            }
    
            $data = array_combine($header, $row);
    
            // Validasi data
            $validator = Validator::make($data, [
                'no_perjanjian' => 'nullable|string',
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
            ]);
    
            if ($validator->fails()) {
                continue; // Lewati baris yang gagal validasi
            }
    
            // Format data untuk insert
            $dataToInsert[] = [
                'no_perjanjian' => !empty($data['no_perjanjian']) ? (string) $data['no_perjanjian'] : null,
                'nama_nasabah' => !empty($data['nama_nasabah']) ? (string) $data['nama_nasabah'] : null,
                'nopol' => !empty($data['nopol']) ? (string) $data['nopol'] : null,
                'coll' => !empty($data['coll']) ? (string) $data['coll'] : null,
                'pic' => !empty($data['pic']) ? (string) $data['pic'] : null,
                'kategori' => !empty($data['kategori']) ? (string) $data['kategori'] : null,
                'jto' => !empty($data['jto']) ? (int) $data['jto'] : null,
                'no_rangka' => !empty($data['no_rangka']) ? (string) $data['no_rangka'] : null,
                'no_mesin' => !empty($data['no_mesin']) ? (string) $data['no_mesin'] : null,
                'merk' => !empty($data['merk']) ? (string) $data['merk'] : null,
                'type' => !empty($data['type']) ? (string) $data['type'] : null,
                'warna' => !empty($data['warna']) ? (string) $data['warna'] : null,
                'status' => !empty($data['status']) ? (string) $data['status'] : null,
                
            ];
        }
    
        // Insert data ke database dalam batch
        foreach (array_chunk($dataToInsert, 5000) as $batch) {
            DB::table('cekunit')->insert($batch);
        }
    
        return redirect()->back()->with('success', 'Data berhasil diimpor!');
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
}