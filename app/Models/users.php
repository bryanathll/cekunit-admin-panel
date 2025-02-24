<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use App\Models\users;

class users extends Model
{
    use HasFactory;

    protected $table = 'user';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable=[
        'nomor',
        'created_at',
        'nama',
        'no_wa',
        'email',
    ];
}
