<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use App\Models\cekunit;

class cekunit extends Model
{
    use HasFactory;

    protected $table = 'cekunit';
    public $timestamps = false;
    protected $primaryKey = 'no_perjanjian';
    protected $keyType = 'string';

    protected $fillable=[
        'no',
        'no_perjanjian',
        'nama_nasabah',
        'nopol',
        'coll',
        'pic',
        'kategori',
        'jto',
        'no_rangka',
        'no_mesin',
        'merk',
        'type',
        'warna',
        'status'
    ];
}

class input_user extends Model
{
    use HasFactory;

    protected $table = 'input_user';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable=[
        'id',
        'created_at',
        'userID',
        'nopol',
        'lokasi',
        'ForN'
    ];
}
