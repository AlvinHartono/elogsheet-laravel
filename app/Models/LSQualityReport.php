<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LSQualityReport extends Model
{
    use HasFactory;

    protected $table = 't_quality_report_refinery';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Tambahkan ini untuk menonaktifkan created_at dan updated_at
    public $timestamps = false;

    protected $fillable = [
        'id',
        'company',
        'plant',
        'transaction_date',
        'posting_date',
        'work_center',
        'oil_type_id',
        'oil_type',
        'shift',
        'time',
        'rm_flow_rate',
        'rm_tank_source',
        'rm_temp',
        'rm_ffa',
        'rm_iv',
        'rm_dobi',
        'rm_av',
        'rm_totox',
        'rm_m&i',
        'rm_pv',
        'rm_color_r',
        'rm_color_y',
        'rm_color_b',
        'bo_color_r',
        'bo_color_y',
        'bo_color_b',
        'bo_break_test',
        'fg_ffa',
        'fg_moisture',
        'fg_impurities',
        'fg_iv',
        'fg_pv',
        'fg_m&i',
        'fg_color_r',
        'fg_color_y',
        'fg_color_b',
        'fg_tank_to',
        'fg_tank_to_others_remarks',
        'bp_ffa',
        'bp_m&i',
        'bp_to_tank',
        'w_sbe_qc',
        'w_sbe_m',
        'w_sbe_m&i',
        'remarks',
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
        'updated_by',
        'updated_date'
    ];


    protected $casts = [
        'transaction_date' => 'datetime',
        'posting_date' => 'datetime',
        'time' => 'datetime:H:i:s',

        'rm_temp' => 'decimal:0',
        'rm_ffa' => 'decimal:3',
        'rm_iv' => 'decimal:3',
        'rm_dobi' => 'decimal:3',
        'rm_av' => 'decimal:3',
        'rm_m&i' => 'decimal:3',
        'rm_pv' => 'decimal:3',
        'rm_color_r' => 'decimal:0',
        'rm_color_y' => 'decimal:3',
        'rm_color_b' => 'decimal:0',

        'fg_ffa' => 'decimal:3',
        'fg_iv' => 'decimal:2',
        'fg_pv' => 'decimal:3',
        'fg_m&i' => 'decimal:3',
        'fg_color_r' => 'decimal:1',
        'fg_color_y' => 'decimal:1',

        'bp_ffa' => 'decimal:3',
        'bp_m&i' => 'decimal:3',
        'w_sbe_qc' => 'decimal:3',

        'entry_date' => 'datetime',
        'prepared_date' => 'datetime',
        'checked_date' => 'datetime',

        'updated_date' => 'datetime',
    ];
}