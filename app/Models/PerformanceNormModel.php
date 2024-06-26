<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class PerformanceNormModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamps = true;
    protected $table = "table_performance_normalization";
    protected $fillable = [
        'id','id_criteria','id_officer','id_performance','norm_value','preference_value'
    ];
}
