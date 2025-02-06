<?php

namespace App\Http\Controllers\User;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cekunit;
use Illuminate\Support\Facades\Redis;

class cekunitController extends Controller
{
        // Controller Download Data
        public function exportCSV(){
        $filename = 'cekunit-' . date('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $cekunits = cekunit::all();
        return new StreamedResponse(function () use ($cekunits) {
            
            $handle = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($handle, [
                'No',
                'No Perjanjian',
                'Nama Nasabah',
                'Nopol',
                'Coll',
                'PIC',
                'Kategori',
                'JTO',
                'No Rangka',
                'No Mesin',
                'Merk',
                'Type',
                'Warna',
                'Status'
            ]);

            // Data CSV
            foreach ($cekunits as $unit) {
                fputcsv($handle, [
                    $unit->no,
                    $unit->no_perjanjian,
                    $unit->nama_nasabah,
                    $unit->nopol,
                    $unit->coll,
                    $unit->pic,
                    $unit->kategori,
                    $unit->jto,
                    $unit->no_rangka,
                    $unit->no_mesin,
                    $unit->merk,
                    $unit->type,
                    $unit->warna,
                    $unit->status
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }


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

    
    public function edit($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        return view('cekunit.edit', compact('unit'));
    }


    public function destroy($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        $unit->delete();
        return redirect()->route('dashboard')->with('success', 'Hapus Data Berhasil');
    }


    public function update(Request $request, $no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        $unit->update($request->all());
        return redirect()->route('dashboard')->with('success', 'Edit Data Berhasil');
    }


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


    public function show($no_perjanjian){
        $unit = cekunit::find($no_perjanjian);
        return view('user.cekunit.show', compact('unit'));
    }
}