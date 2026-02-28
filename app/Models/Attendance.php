<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'client_id',
        'attendance_date',
        'user_type',
        'entity_id',
        'user_id',
        'status',
        'remark',
        'marked_by',
    ];
}
