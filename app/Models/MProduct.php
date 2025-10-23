<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProduct extends Model
{
    use HasFactory;

    protected $table = 'm_product';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'raw_material',
        'finish_good',
        'by_product',
        'process_name',
        'isactive',
    ];
}
