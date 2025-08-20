<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MMastervalue extends Model
{
    use HasFactory;

    protected $table = 'm_mastervalue';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'group',
        'number',
        'isactive',
        'entry_by',
        'entry_date'
    ];
}
