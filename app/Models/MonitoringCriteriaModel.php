<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class MonitoringCriteriaModel extends Model
{
    use HasFactory;
    use Uuid,SoftDeletes;

    public $timestamps = true;
    protected $table = "table_monitoring_criteria";
    protected $fillable = [
        'id','mc_name','description','id_period','weight','id_officer'
    ];
    
}
