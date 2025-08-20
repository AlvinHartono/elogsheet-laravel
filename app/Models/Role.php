<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'm_role';
    protected $primaryKey = 'role_code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;


    protected $fillable = [
        'role_code',
        'role_name',
    ];
}
