<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class PerformanceRankModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamps = true;
    protected $table = "table_performance_ranking";
    protected $fillable = [
        'id','id_officer','id_period','rank_value'
    ];
}
