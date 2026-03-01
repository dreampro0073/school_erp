<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    protected $table = 'attendance_statuses';

    protected $fillable = [
        'code',
        'label',
        'badge_class',
        'bar_class',
        'active',
        'sort_order',
        'is_default',
    ];
}
