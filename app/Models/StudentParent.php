<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentParent extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $guarded = [];
}