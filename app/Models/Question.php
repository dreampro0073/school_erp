<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'question',
        'question_hi',
        'remarks',
        'reference',
        'opt_a',
        'opt_b',
        'opt_c',
        'opt_d',
        'answer',
        'negative_marks',
        'paragraph_id',
        'image_file',
        'total_marks',
        'subject_id',
        'topic_id',
    ];
}
