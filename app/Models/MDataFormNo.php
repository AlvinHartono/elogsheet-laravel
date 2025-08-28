<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDataFormNo extends Model
{
    use HasFactory;
    protected $table = 'm_data_form_no';
    protected $primaryKey = 'f_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'f_id',
        'f_code',
        'f_name',
        'f_date_issued',
        'f_revision_no',
        'f_revision_date',
        'is_active',
        'is_menu',
        'entry_by',
        'entry_date',
    ];
}
