<?php

namespace App\Http\Controllers\User;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cekunit;
use App\Models\input_user;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



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
// ============================================== end Controller index ================================================



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
    public function edit($no_perjanjian)
        {
            $unit = cekunit::find($no_perjanjian);
            return view('cekunit.edit', compact('unit'));
        }
// =============================================== end Controller edit ================================================ 


    
// ============================================= start Controller delete =============================================
    public function destroy($no_perjanjian)
        {
            $unit = cekunit::find($no_perjanjian);
            $unit->delete();
            return redirect()->route('dashboard')->with('success', 'Hapus Data Berhasil');
        }
// ============================================== end Controller delete ==============================================



// ============================================= Start Controller update =============================================
    public function update(Request $request, $no_perjanjian)
        {
            $unit = cekunit::find($no_perjanjian);
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
    public function show($no_perjanjian)
        {
            $unit = cekunit::find($no_perjanjian);
            return view('user.cekunit.show', compact('unit'));
        }
// =============================================== end Controller show =============================================== 
}
