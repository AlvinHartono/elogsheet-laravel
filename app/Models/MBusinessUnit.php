<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBusinessUnit extends Model
{
    use HasFactory;

    protected $table = 'm_business_unit';
    protected $primaryKey = 'bu_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'bu_code',
        'bu_name',
        'isactive',
    ];

    // Relasi ke LSQualityReport
    public function qualityReports()
    {
        return $this->hasMany(LSQualityReport::class, 'plant', 'bu_code');
    }

    public function lampGlassReports()
    {
        return $this->hasMany(LSLampGlassHeader::class, 'plant', 'bu_code');
    }
}
