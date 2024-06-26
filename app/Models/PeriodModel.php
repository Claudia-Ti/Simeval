<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class PeriodModel extends Model
{
    use HasFactory;
    use Uuid, SoftDeletes;
    public $timestamps = true;
    protected $table = "table_period";
    protected $fillable = [
        'id','p_name','description'
    ];
}
