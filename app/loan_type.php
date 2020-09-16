<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_type extends Model
{
    //
    protected $fillable = [
      'name','code','interest','max_recovery_months','max_amount', 'loan_fee_percentage', 'insurance_fee_percentage'
    ];

    public function loan(){
        return $this->hasMany('App\loan', 'code', 'type');
    }
}
