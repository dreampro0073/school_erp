<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $appends = ['right_answer'];

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
        'passage_id',
        'image_file',
        'total_marks',
        'subject_id',
        'topic_id',
    ];

    public function getRightAnswerAttribute()
    {
        if (!$this->answer) {
            return null;
        }

        return $this->{'opt_' . strtolower($this->answer)};
    }

    public function passage()
    {
        return $this->belongsTo(Passage::class);
    }
}
