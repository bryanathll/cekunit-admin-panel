<?php

namespace App\Http\Controllers\User;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\users;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class usersController extends Controller
{

// =========================================== Start Controller update users ==========================================
    public function update(Request $request, $nomor)
    {
        $unit = users::where('nomor', $nomor)->first();

        if(!$unit){
            return redirect()->route('users')->with('error', 'Data tidak ditemukan');
        }

        $unit->update($request->all());
        return redirect()->route('users')->with('success', 'Edit Data Berhasil');
    }
// ============================================ end Controller update users =========================================== 



// ============================================ start Controller users ================================================ 
    // Method untuk menangani kedua kasus: tampilan utama dan permintaan AJAX
    public function users(Request $request) {
        // Ambil parameter dari request
        $sort = $request->query('sort', 'created_at'); // Parameter sorting (default: 'no')
        $direction = $request->query('direction', 'asc'); // Parameter arah sorting (default: 'asc')
    
        // Query data
        $query = users::orderBy($sort, $direction);
        $users = $query
        ->paginate(20) // Pagination
        ->appends([
            'sort' => $sort,
            'direction' => $direction
        ]);
    
        // Jika request AJAX, kembalikan view pagination saja
        if ($request->ajax()) {
            return view('pages.user.tableUsers', compact('users', 'sort', 'direction'))->render();
        }
    
        // Jika bukan AJAX, kembalikan view lengkap
        return view('pages.user.users', compact('users', 'sort', 'direction'));
    }
// ================================================ end Controller users ==============================================
}