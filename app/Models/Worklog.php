<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worklog extends Model
{
    protected $table = 'worklog';

    protected $fillable = [
        'user_id',
        'client_id',
        'date',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
