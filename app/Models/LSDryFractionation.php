<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LSDryFractionation extends Model
{
    use HasFactory;

    protected $table = 't_dry_fractionation';
    protected $primaryKey = 'id';

    // ID diisi manual dengan angka (running number)
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'company',
        'plant',
        'transaction_date',
        'posting_date',
        'work_center',
        'shift',
        'oil_type_id',
        'oil_type',
        'crystalizier',
        'filling_start_time',
        'filling_end_time',
        'colling_start_time',
        'initial_oil_level',
        'initial_tank',
        'feed_iv',
        'agitator_speed',
        'water_pump_press',
        'crystal_start_time',
        'crystal_temp',
        'filtration_start_time',
        'filtration_temp',
        'filtration_cycle_no',
        'filtration_oil_level',
        'olein_iv_red',
        'olein_cloud_point',
        'stearin_iv',
        'stearin_slep_point_red',
        'olein_yield',
        'remarks',
        'flag',
        'entry_by',
        'entry_date',
        'prepared_by',
        'prepared_date',
        'prepared_status',
        'prepared_status_remarks',
        'checked_by',
        'checked_date',
        'checked_status',
        'checked_status_remarks',
        'updated_date',
        'updated_status',
        'form_no',
        'date_issued',
        'revision_no',
        'revision_date',
    ];

    // NOTE: kalau kolom waktu di DB bertipe TIME, sebaiknya hapus cast datetime berikut
    protected $casts = [
        'transaction_date' => 'datetime',
        'posting_date' => 'datetime',
        'entry_date' => 'datetime',
        'prepared_date' => 'datetime',
        'checked_date' => 'datetime',
        'updated_date' => 'datetime',
        'date_issued' => 'date',
        'revision_date' => 'date',

        // numeric
        'initial_oil_level' => 'decimal:2',
        'feed_iv' => 'decimal:2',
        // 'agitator_speed' => 'decimal:2',
        'water_pump_press' => 'decimal:2',
        // 'filtration_oil_level' => 'decimal:2',
        'olein_iv_red' => 'decimal:2',
        'olein_cloud_point' => 'decimal:2',
        'stearin_iv' => 'decimal:2',
        'stearin_slep_point_red' => 'decimal:2',
        'olein_yield' => 'decimal:2',

        'filtration_cycle_no' => 'integer',
        'revision_no' => 'integer',
    ];
}
