<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPlant extends Model
{
    use HasFactory;

    protected $table = 'm_plant';
    protected $primaryKey = 'plant_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'plant_code',
        'plant_name',
        'bu_code',
        'isactive',
    ];
}
