<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model{
    protected $table = 'transport_routes';
    public $timestamps = false;

    protected $guarded = [];

    public function feeFrequency(){
        return $this->belongsTo(FeeFrequency::class, 'frequency_id');
    }

}

