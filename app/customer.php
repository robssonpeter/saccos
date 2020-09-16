<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    //
    protected $fillable = [
        'key','registration_id','user_id','deposits_balance','shares','savings_balance','status'
    ];

    public function User(){
        return $this->hasOne('App\User');
    }

    public function registration(){
        return $this->hasOne('App\registration');
    }

    public function loan(){
        return $this->hasMany('App\loan', 'customer_key', 'key');
    }

    public function cash_flow(){
        return $this->hasMany('App\cash_flow', 'customer_id', 'key');
    }

    public function withdrawal_request(){
        return $this->hasMany('App\withdrawal_request', 'customer_key', 'key');
    }
}
