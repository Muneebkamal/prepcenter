<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyInputs extends Model
{
    use HasFactory;
    protected $fillable = ['emloyee_id', 'date', 'start_time', 'end_time'];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function dailyInput()
    {
        return $this->belongsTo(DailyInputs::class, 'daily_input_id');
    }
}
