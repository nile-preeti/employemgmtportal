<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'user_id',
        'check_in_full_address', 'check_in_latitude', 'check_in_longitude', 'check_in_time',
        'check_out_full_address', 'check_out_latitude', 'check_out_longitude', 'check_out_time',
        'status'
    ];
}
