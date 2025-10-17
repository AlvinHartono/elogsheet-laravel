<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use app\Models\LSQualityReport;
// use app\Models\LSQualityReportQc;


class MRolesShiftPrepared extends Model
{
    use HasFactory;

    protected $table = 'm_roles_shift_prepared';
    protected $primaryKey = 'id';
    protected $fillable = ['shift_code', 'shift_nama', 'username', 'isactive'];

    public $timestamps = false;

    public function leaderShiftTicketProd()
    {
        return $this->belongsTo(LSQualityReport::class, 'shift_code', 'shift');
    }

    public function leaderShiftTicketQc()
    {
        return $this->belongsTo(LSQualityReportQc::class, 'shift_code', 'shift');
    }
}
?>