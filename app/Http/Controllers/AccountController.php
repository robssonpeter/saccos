<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function RequestWithdrawal(){
        return view('withdraw.request');
    }

    public function AccountTypes(){
        $types = config('app.account_types');
        $output = [];
        $rates = [];

        foreach ($types as $type){
            if($type['withdrawable']){
                array_push($output, [
                    'name'=>$type['name'],
                ]);
            }
        }
        $final = [
            'output' => $output,
        ];
        return json_encode($final);
    }

    public function WithdrawMaxAmount(Account $account){
        $type = \request()->account;
        $withdrawable = $account->Withdrawable(Auth()->user()->customer_key, $type);
        return $withdrawable;
    }

    public function WithdrawRequestSubmit(){
        $data = \request()->all();
        $data['customer_key'] = Auth()->user()->customer_key;
        $data['approved'] = 0;
        $data['collected'] = 0;

        if(\App\withdrawal_request::create($data)){
            return redirect()->route('home');
        }
    }
}
