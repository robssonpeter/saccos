<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class registration extends Model
{
    //
    protected $fillable = [
        'name','birth_date','occupation','adress','phone','sub_parish','zone','congregation','heir_full_name','heir_house_number',
        'heir_relation','ref_1_id','ref_2_id','confirmed','user_id', 'house_number', 'customer_id'
    ];

    public function User(){
        return $this->belongsTo('App\User');
    }

    public function Customer(){
        return $this->belongsTo('\App\customer');
    }

    public function Loan(){
        return $this->hasMany('App\loan', 'customer_key', 'customer_id');
    }
}
