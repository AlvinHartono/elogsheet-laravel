<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MLangkahKerja extends Model
{
    use HasFactory;
    protected $table = 'm_langkahkerja';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'sort_no',
        'category',
        'work_center',
        'work_center_group',
        'isactive',
    ];

    protected $casts = [
        'sort_no' => 'integer',
    ];
}
