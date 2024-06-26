<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;


class RankCategoryModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamp = true;
    protected $table = "table_rank_category";
    protected $fillable = [
        "id","name","initial","min_value"
    ];
}
