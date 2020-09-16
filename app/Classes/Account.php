<?php


namespace App\Classes;


class Account
{
    public static function CashFlow(String $customer_key, $flow_type, $target_account, $amount, $description = null){
        $cash_flow = [
            'customer_id' => $customer_key,
            'nature' => $flow_type,
            'type' => $target_account,
            'description' => $description,
            'amount' => $amount
        ];
        switch($target_account){
            case 'savings':
                if($flow_type == 'in'){
                   if( \App\cash_flow::create($cash_flow)){
                       \App\customer::where('key', $customer_key)->increment('savings_balance', $amount);
                       return true;
                   }
                }else{
                    if( \App\cash_flow::create($cash_flow)){
                        \App\customer::where('key', $customer_key)->decrement('savings_balance', $amount);
                        return true;
                    }
                }
                break;
            case 'deposits':
                if($flow_type == 'in'){
                    if( \App\cash_flow::create($cash_flow)){
                        \App\customer::where('key', $customer_key)->increment('deposits_balance', $amount);
                        return true;
                    }
                }else{
                    if( \App\cash_flow::create($cash_flow)){
                        \App\customer::where('key', $customer_key)->decrement('deposits_balance', $amount);
                        return true;
                    }
                }
                break;
            case 'shares':
                $price_per_share = \App\share_price::orderBy('id', 'DESC')->first();
                if(!is_null($price_per_share)){
                    $share_price = $price_per_share->amount;
                    $cash_flow['description'].= "*Price Per Share: ".$share_price."*";
                    $shares = $amount/$share_price;
                    if($flow_type == 'in'){
                        if( \App\cash_flow::create($cash_flow)){
                            \App\customer::where('key', $customer_key)->increment('shares', $shares);
                            return true;
                        }
                    }else{
                        if( \App\cash_flow::create($cash_flow)){
                            \App\customer::where('key', $customer_key)->decrement('shares', $shares);
                            return true;
                        }
                    }
                }
                break;

        }
    }

    public function Withdrawable($customer_key, $account_type){
        $customer = \App\customer::where('key', $customer_key)->first();
        $account = config('app.account_types')[$account_type];

        if(is_array($account)){
            if($account['unit']=='currency'){
                $column = $account['name']."_balance";
            }else{
                $column = $account['name'];
            }

            return [
                'max_amount'=> $customer[$column]-$account['minimum_balance'],
            ];
        }
    }

}
