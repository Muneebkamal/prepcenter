<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'item',
        'asin',
        'msku',
        'fnsku',
        'pack',
        'created_at',
        'updated_at',
    ];
    public function dailyInputDetails()
    {
        return $this->hasMany(DailyInputDetail::class, 'fnsku', 'fnsku');
    }
}
