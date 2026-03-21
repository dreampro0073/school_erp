<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Student,App\Models\StudentParent,App\Models\MasterData, App\Models\User,App\Models\ClientStandard;
use DB;

use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller{
    public function generateReceipt($payment_id=0){
        $pdf = Pdf::loadView('admin.students.fee_receipt');
        return $pdf->download('receipt_'.$payment_id.'.pdf');
    }
}
