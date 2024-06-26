<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class MonitoringReportModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamps = true;
    protected $table = "table_monitoring_report";
    protected $fillable = [
        'id','id_officer','id_mon_crit','id_period','mon_value','preference_value','notes'
    ];
}
