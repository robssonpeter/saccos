<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan extends Model
{
    //
    protected $fillable = [
        'customer_key','type','rate','recovery_months','amount','accepted', 'disbursement_method', 'disbursement_date'
    ];

    public function customer(){
        return $this->belongsTo('App\customer', 'customer_key', 'key');
    }

    public function loan_schedule(){
        return $this->hasMany('App\loan_schedule');
    }

    public function cash_flow(){
        return $this->hasMany('App\cash_flow');
    }

    public function user(){
        return $this->hasOne('App\User', 'customer_key', 'customer_key');
    }

    public function loan_type(){
        return $this->belongsTo('App\loan_type', 'type', 'code');
    }

    public function registration(){
        return $this->belongsTo('App\registration', 'customer_key', 'customer_id');
    }
}
