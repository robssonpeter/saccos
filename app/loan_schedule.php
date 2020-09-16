<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_schedule extends Model
{
    //
    protected $fillable = [
        'loan_id', 'customer_id', 'expected_pay_date', 'amount', 'paid_amount', 'paid', 'interest', 'opening_balance', 'closing_balance', 'installment_number'
    ];

    public function loan(){
        return $this->belongTo('App\loan');
    }

    public function customer(){
        return $this->belongTo('App\customer');
    }


}
