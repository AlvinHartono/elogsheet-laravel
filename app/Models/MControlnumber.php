<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MControlnumber extends Model
{
    use HasFactory;
    protected $table = 'm_controlnumber';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'lu_name',
        'accountingyear',
        'accountingperiod',
        'prefix',
        'suffix',
        'autonumber',
        'lengthpad',
        'plantid',
        'plantname',
        'location',
        'imenu',
        'category_apps',

    ];
}
