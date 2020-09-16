<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class share_price extends Model
{
    //
    protected $fillable = [
        'date', 'amount', 'user_id'
    ];
}
