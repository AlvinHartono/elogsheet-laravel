<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LSStartUpProduksiChecklistDetail extends Model
{
    use HasFactory;

    protected $table = 't_change_product_checklist_detail';

    protected $keyType = 'string';

    public $timestamps = false;


    protected $fillable = [
        'id',
        'id_hdr',
        'check_item',
        'status_item',
    ];


    protected $casts = [
        'id' => 'string',
        'id_hdr' => 'string',
        'check_item' => 'string',
        'status_item' => 'string',
    ];

    public function header()
    {
        return $this->belongsTo(LSStartUpProduksiChecklistHeader::class, 'id_hdr', 'id');
    }

    public function langkahKerjaStartup()
    {
        return $this->belongsTo(MLangkahKerjaStartup::class, 'check_item', 'code');
    }

}
