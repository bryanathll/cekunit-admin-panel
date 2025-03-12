<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\pic_collector;


class picController extends Controller
{
    public function create()
{
    // Default sorting
    $sort = request()->get('sort', 'nomor'); // Kolom default untuk sorting
    $direction = request()->get('direction', 'asc'); // Arah default untuk sorting

    // Query data
    $query = pic_collector::orderBy($sort, $direction);
    $pic = $query->paginate(20)->appends([
        'sort' => $sort,
        'direction' => $direction
    ]);

    // Kirim data ke view
    return view('pages.user.pic_collector', compact('pic', 'sort', 'direction'));
}

// ============================================= start Controller store ==============================================
    public function store(Request $request)
    {   
        // Validasi data
        $request->validate([
            'id_coll' => 'required',
            'nama_collector' => 'nullable',
            'no_wa' => 'nullable',
            'status' => 'nullable',
        ]);

        try {
            // Insert data ke database
            pic_collector::create([
                'id_coll' => $request->id_coll,
                'nama_collector' => $request->nama_collector,
                'no_wa' => $request->no_wa,
                'status' => $request->status,
            ]);

            // Redirect ke halaman input dengan pesan sukses
            return redirect()->route('input.PIC')->with('success', 'Data Berhasil Di Input');

        } catch (\Exception $e) {
            // Redirect dengan pesan error jika gagal
            return redirect()->route('input.PIC')->with('error', 'Data Gagal Di Input: ' . $e->getMessage());
        }
    }
// ============================================== end Controller store ===============================================



// ============================================= start Controller pic ==============================================
public function pic(Request $request){
    $sort = $request->query('sort', 'nomor');
    $direction = $request->query('direction','asc');
    
    // Query data
    $query = pic_collector::orderBy($sort, $direction);
    $pic = $query->paginate(20)->appends([
        'sort' => $sort,
        'direction' => $direction
    ]);
    
    // Jika request AJAX, kembalikan view partial (pic_collector_data.blade.php)
    if ($request->ajax()) {
        return view('pages.user.pic_collector_data', compact('pic', 'sort', 'direction'))->render();
    }
    
    // Jika bukan AJAX, kembalikan view lengkap (pic_collector.blade.php)
    return view('pages.user.pic_collector', compact('pic', 'sort', 'direction'));
}
// ============================================== end Controller pic ===============================================    



// ============================================= start Controller update ==============================================
    public function update(Request $request, $nomor){
        $picupdt = pic_collector::where('nomor', $nomor)->first();
        
        if(!$picupdt){
            return redirect()->route('pic')->with('error', 'Data tidak ditemukan');
        }
        
        // Hanya ambil field yang diperlukan dari request
        $data = $request->only(['id_coll', 'nama_collector', 'no_wa', 'status']);
        
        // Update dengan data yang sudah difilter
        $picupdt->update($data);
        
        return redirect()->route('pic')->with('success', 'Edit Data Berhasil');
    }
// ============================================== end Controller update ===============================================



// ============================================= start Controller delete ==============================================
    public function destroy($nomor)
    {
        $delete = pic_collector::find($nomor);
        $delete->delete();
        return redirect()->route('pic')->with('success', 'Hapus Data Berhasil');
    }
// ============================================== end Controller delete ===============================================
}
