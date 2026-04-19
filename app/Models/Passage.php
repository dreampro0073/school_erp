<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passage extends Model
{
    protected $table = 'passages';

    protected $fillable = [
        'title',
        'passage',
        'subject_id',
        'topic_id',
        'status',
    ];
}
