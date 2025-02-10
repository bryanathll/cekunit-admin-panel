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

class cekunitController extends Controller
{
    // ======== start Controller Download Data ======== 
    //     public function exportCSV(){
    //     $filename = 'cekunit-' . date('Ymd') . '.csv';
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"$filename\"",
    //     ];

    //     $cekunits = cekunit::all();
    //     return new StreamedResponse(function () use ($cekunits) {
            
    //         $handle = fopen('php://output', 'w');
            
    //         // Header CSV
    //         fputcsv($handle, [
    //             'No',
    //             'No Perjanjian',
    //             'Nama Nasabah',
    //             'Nopol',
    //             'Coll',
    //             'PIC',
    //             'Kategori',
    //             'JTO',
    //             'No Rangka',
    //             'No Mesin',
    //             'Merk',
    //             'Type',
    //             'Warna',
    //             'Status'
    //         ]);
            
    //         // Data CSV
    //         foreach ($cekunits as $unit) {
    //             fputcsv($handle, [
    //                 $unit->no,
    //                 $unit->no_perjanjian,
    //                 $unit->nama_nasabah,
    //                 $unit->nopol,
    //                 $unit->coll,
    //                 $unit->pic,
    //                 $unit->kategori,
    //                 $unit->jto,
    //                 $unit->no_rangka,
    //                 $unit->no_mesin,
    //                 $unit->merk,
    //                 $unit->type,
    //                 $unit->warna,
    //                 $unit->status
    //             ]);
    //         }
            
    //         fclose($handle);
    //     }, 200, $headers);
    // }
    // //=============  end Controller Download Data ============= 
    


    // ================ start Controller index ================ 
    // Method untuk menangani kedua kasus: tampilan utama dan permintaan AJAX
    public function index(Request $request)
        {

            $sort = $request->query('sort', 'no'); //default sort column
            $direction = $request->query('direction', 'asc'); //default sort direction

            // Ambil data dengan pagination
            $cekunit = cekunit::orderBy($sort, $direction)->paginate(20);

            // Jika permintaan AJAX, kembalikan view pagination saja
            if ($request->ajax()) {
                return view('pages.user.pagination_table', compact('cekunit', 'sort', 'direction'))->render();
            }

            // Jika bukan AJAX, kembalikan view lengkap
            return view('pages.user.dashboard', compact('cekunit', 'sort', 'direction'));
        }
    // ================ end Controller index ================

    // ================ start Controller input_ueser ================    
    public function input_user(Request $request)
        {

            $sort = $request->query('sort', 'id'); //default sort column
            $direction = $request->query('direction', 'asc'); //default sort direction

            // Ambil data dengan pagination
            $input_user = input_user::orderBy($sort, $direction)->paginate(20);

            // Jika permintaan AJAX, kembalikan view pagination saja
            if ($request->ajax()) {
                return view('pages.user.input_user', compact('input_user', 'sort', 'direction'))->render();
            }

            // Jika bukan AJAX, kembalikan view lengkap
            return view('pages.user.input_user_table', compact('input_user', 'sort', 'direction'));
        }
    // ================ end Controller input_ueser ================

    // ================ start Controller edit ================
    public function edit($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        return view('cekunit.edit', compact('unit'));
    }
    // ================ end Controller edit ================
    
    // ================ start Controller delete ================
    public function destroy($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        $unit->delete();
        return redirect()->route('dashboard')->with('success', 'Hapus Data Berhasil');
    }
    // ================ end Controller delete ================
    
    // ================ start Controller update ================    
    public function update(Request $request, $no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        $unit->update($request->all());
        return redirect()->route('dashboard')->with('success', 'Edit Data Berhasil');
    }
    // ================ end Controller update ================    


    public function sort(Request $request){

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

    public function sort_input_user(Request $request){

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


    public function show($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        return view('user.cekunit.show', compact('unit'));
    }


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
}