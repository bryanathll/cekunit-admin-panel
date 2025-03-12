<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use App\Models\pic_collector;

class pic_collector extends Model
{
    use HasFactory;

    protected $table = 'pic_collector';
    public $timestamps = false;
    protected $primaryKey = 'nomor';
    protected $keyType = 'integer';
    

    protected $fillable=[
        'nomor',
        'user_id',
        'id_coll',
        'nama_collector',
        'no_wa',
        'status'
    ];
}
