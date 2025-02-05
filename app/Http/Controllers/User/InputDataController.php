<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\cekunit;


class InputDataController extends Controller
{
    public function create()
    {
        return view('pages.user.input_data');
    }

    public function store(Request $request)
    {

        $request->validate([
            'no_perjanjian' => 'required|string',
            'jto' => 'numeric'

        ]);

        cekunit::create([
            'no_perjanjian'=>$request->no_perjanjian,
            'nama_nasabah'=>$request->nama_nasabah,
            'nopol'=>$request->nopol,
            'coll'=>$request->coll,
            'pic'=>$request->pic,
            'kategori'=>$request->kategori,
            'jto'=>$request->jto,
            'no_rangka'=>$request->no_rangka,
            'no_mesin'=>$request->no_mesin,
            'merk'=>$request->merk,
            'type'=>$request->type,
            'warna'=>$request->warna,
            'status'=>$request->status    
        ]);
    return redirect()->route('input.data')->with('success','Data Berhasil Di Input');
    }
}
