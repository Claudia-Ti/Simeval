<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class CriteriaModel extends Model
{
    use HasFactory;
    use SoftDeletes,Uuid;
    public $timestamps = true;
    protected $table = "table_criteria";
    protected $fillable = [
        'id','c_name','c_type','weight','id_period','id_officer'
    ];
}
