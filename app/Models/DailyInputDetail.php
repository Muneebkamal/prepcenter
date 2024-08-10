<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyInputDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'daily_input_details';

    // Define the inverse of the one-to-one relationship
    public function product()
    {
        return $this->belongsTo(Products::class, 'fnsku', 'fnsku');
    }
    
    public function details()
    {
        return $this->hasMany(DailyInputDetail::class, 'daily_input_id');
    }
}
