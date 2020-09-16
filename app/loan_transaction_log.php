<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_transaction_log extends Model
{
    //
    protected $fillable = [
        'installment_number','loan_id', 'paid_amount'
    ];

    public function loan(){
        return $this->belongsTo('App\loan');
    }
}
