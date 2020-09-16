<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cash_flow extends Model
{
    //
    protected $fillable = [
        'customer_id', 'loan_id', 'nature', 'type', 'description', 'amount'
    ];

    public function customer(){
        return $this->belongsTo('App\customer', 'key', 'customer_id');
    }

    public function loan(){
        return $this->belongsTo('App\loan');
    }


}
