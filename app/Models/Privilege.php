<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $table = 'privileges';

    protected $primaryKey = 'priv';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $guarded = [];
}

