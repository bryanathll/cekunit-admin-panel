<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use App\Models\input_user;

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
