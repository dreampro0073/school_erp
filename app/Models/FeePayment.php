<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\FeeType,App\Models\PaymentMode;

class FeePayment extends Model
{
    protected $table = 'fee_payments';
    public $timestamps = false;

    protected $guarded = [];

    public function feeType(){
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }
    public function paymentMode(){
        return $this->belongsTo(PaymentMode::class, 'payment_mode');
    }

    public static function getFeeFrequencies($school_id){
        return DB::table('fee_frequencies')->select('id as value','name as label')->where(['school_id'=>$school_id])->get();
    }


    public static function getPaymentModes(){
        return DB::table('payment_modes')->select('id as value','name as label')->get();
    }

    public static function getMonths(){
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                "value" => $i,
                "label" => date('M', mktime(0, 0, 0, $i, 1))
            ];
        }

        return $months;
    }
    public static function getFeeAmount($school_id, $fee_type_id, $standard_id, $frequency_id = null){
        $fee_subs = DB::table('fee_structures')
            ->select('id','amount')
            ->where([
                'school_id' => $school_id,
                'fee_type_id' => $fee_type_id,
                'standard_id' => $standard_id
            ]);

        if (!empty($frequency_id)) {
            $fee_subs->where('frequency_id', $frequency_id);
        }
        $fee_subs = $fee_subs->first();
        return ($fee_subs)?$fee_subs->amount : 0;
    }
}

