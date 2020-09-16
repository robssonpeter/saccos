<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class withdrawal_request extends Model
{
    //
    protected $fillable = [
        'customer_key','type','amount','approved','collected'
    ];

    public function customer(){
        return $this->belongsTo('App\customer', 'customer_key', 'key');
    }
}
