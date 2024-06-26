<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class OfficerModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamps = true;
    protected $table = "table_officer";
    protected $fillable = [
        'id','o_name','position','unit','gender','dob'
    ];
    protected $casts = [
        'dob'=>'date'
    ];
}
