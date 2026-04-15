<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    protected $guarded = [];

    protected $casts = [
        'selected_subject_ids' => 'array',
        'start_time' => 'datetime',
        'submitted_at' => 'datetime',
        'total_score' => 'decimal:2',
    ];
}
