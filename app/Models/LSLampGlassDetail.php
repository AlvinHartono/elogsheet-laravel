<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LSLampGlassDetail extends Model
{
  use HasFactory;

  protected $table = 't_checklist_lamps_glass_control_detail';
  protected $keyType = 'string';
  public $timestamps = false;

  protected $fillable = [
    'id',
    'id_hdr',
    'check_item',
    'status_item'
  ];

  protected $casts = [
    'id' => 'string',         // jika UUID/string
    'id_hdr' => 'string',     // relasi ke header
    'check_item' => 'string', // kode ICxx
    'status_item' => 'string', // kalau status berupa T/F atau 0/1
  ];

  public function header()
  {
    return $this->belongsTo(LSLampGlassHeader::class, 'id_hdr', 'id');
  }
}
