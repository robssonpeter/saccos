<?php


namespace App\Classes;


use App\loan_schedule;

class Loan
{
    public static function Populate(){
        $types = \App\loan_type::all();
        $configTypes = config('database.loan_types');
        $count = 0;
        foreach ($configTypes as $type){
            if($types->where('code', $type['code'])->count()==0){
                \App\loan_type::create([
                    'name'=>$type['name'],
                    'code' => $type['code'],
                    'interest' => $type['rate'],
                    'max_recovery_months'=>$type['max_recovery_months'],
                    'loan_fee_percentage'=>$type['loan_fee_percentage'],
                    'insurance_fee_percentage' => $type['insurance_fee_percentage'],
                    'max_amount'=>$type['max_amount']
                ]);
                $count++;
            }
        }
        return $count;
    }

    public static function Loanable($customer_key, $loan_type){
        $customer = \App\customer::where('key', $customer_key)->first();
        $loanable = $customer->savings_balance * 2;
        $loan = \App\loan_type::where('code', $loan_type)->first();
        if(!is_null($loan) && $loan->max_amount){
            $return = [
                'loanable'=>$loan->max_amount,
                'months' => $loan->max_recovery_months,
                'loan_fee' => $loan->loan_fee_percentage,
                'insurance_fee'=>$loan->insurance_fee_percentage
            ];
            return json_encode($return);
        }
        $return = [
            'loanable'=>$loanable,
            'months' => $loan->max_recovery_months,
            'loan_fee' => $loan->loan_fee_percentage,
            'insurance_fee'=>$loan->insurance_fee_percentage
        ];
        return json_encode($return);
    }

    public static function PaymentSchedule($loanAmount, $interestRate, $recoveryMonths, $disburse_date = null){
        $OpeningBalance = $loanAmount;
        $originalMonths = $recoveryMonths;
        $principle = round($OpeningBalance/$recoveryMonths, 2);
        $monthlyPay = round($principle+($interestRate*$loanAmount), 2);
        $schedule = [];
        $current_month = 1;
        $plusDays = 31;

        while($recoveryMonths>0){
            $pay_date = strtotime('+'.$plusDays.' days');
            $principle = round($OpeningBalance/$recoveryMonths, 2);
            if($OpeningBalance<$monthlyPay){
                //$monthlyPay = $principle+($interestRate*$loanAmount);
                $monthlyPay = $OpeningBalance;
                $principle = $OpeningBalance;
            }
            $interest = round($loanAmount*$interestRate, 2);
            $ClosingBalance = $OpeningBalance-$principle;
            $toArray = [
                'current_month' => $current_month,
                'expected_pay_date' => date('Y-m-d', $pay_date),
                'opening_balance' => $loanAmount,
                'interest' => $interest,
                'principle' => $principle,
                'monthly_payment' => $monthlyPay,
                'closing_balance' => $ClosingBalance
            ];
            array_push($schedule, $toArray);
            $OpeningBalance = $ClosingBalance;
            $loanAmount = $OpeningBalance;
            $recoveryMonths--;
            $current_month++;
            $plusDays+=31;
        }
        return $schedule;
    }

    public static function Repayment($loan_id, $amount){
        $unpaid = \App\loan_schedule::where('loan_id', $loan_id)->where('paid', 0)->orderBy('installment_number', 'ASC')->get();
        $current = $unpaid[0];
        $installment = \App\loan_schedule::where('loan_id', $loan_id)->where('installment_number', $current->installment_number)->first();
        if($current->paid_amount==0){
            if($amount==$current->amount){
                /*if the amounts match then update the paid amount and mark the installment as paid*/
                $installment->increment('paid_amount', $amount);
                \App\loan_schedule::where('loan_id', $loan_id)->where('installment_number', $current->installment_number)->update(['paid'=>1]);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$amount]);
            }else if($amount>$current->amount){ // if the amount has been paid in excess make necessary adjustments and move the remainder to the next installment
                $installment->increment('paid_amount', $current->amount);
                \App\loan_schedule::where('loan_id', $loan_id)->where('installment_number', $current->installment_number)->update(['paid'=>1]);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$current->amount]);
                self::Repayment($loan_id, $amount-$current->amount);
            }else if($amount<$current->amount){
                $installment->increment('paid_amount', $amount);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$amount]);
                //add the added amount to the paid amount
            }else if($amount > $current->amount){
                if(self::LoanCompleted($loan_id)){
                    $loan = \App\loan::find($loan_id);
                    $remainder = $amount - $current->amount;
                    $outstanding_loan = self::IsOnLoan($loan->customer_key);
                    if($outstanding_loan){
                        self::Repayment($outstanding_loan, $amount-$remainder);
                    }else{
                        $description = 'Amount from Loan Overpay, Loan ID: '.$loan->id;
                        \App\Classes\Account::CashFlow($loan->customer_key, 'in', 'savings', $remainder, $description);
                    }
                }
            }
        }else{
            /*if the paid amount is greater than zero find the remaining amount*/
            $remaining = $current->amount - $current->paid_amount;
            if($amount==$remaining){
                // update the paid amount and mark the installment as paid
                \App\loan_schedule::where('loan_id', $loan_id)->where('installment_number', $current->installment_number)->increment('paid_amount', $amount);
                $installment->update(['paid'=>1]);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$amount]);
            }else if($amount>$remaining){
                $installment->increment('paid_amount', $remaining);
                \App\loan_schedule::where('loan_id', $loan_id)->where('installment_number', $current->installment_number)->update(['paid'=> 1]);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$remaining]);
                self::Repayment($loan_id, $amount-$remaining);
            }else if($amount>$remaining){
                $installment->increment('paid_amount', $amount);
                \App\loan_transaction_log::create(['loan_id'=>$loan_id, 'installment_number'=>$current->installment_number, 'paid_amount'=>$amount]);
            }else if($amount > $current->amount){
                if(self::LoanCompleted($loan_id)){
                    $loan = \App\loan::find($loan_id);
                    $remainder = $amount - $current->amount;
                    $outstanding_loan = self::IsOnLoan($loan->customer_key);
                    if($outstanding_loan){
                        self::Repayment($outstanding_loan, $amount-$remainder);
                    }else{
                        $description = 'Amount from Loan Overpay, Loan ID: '.$loan->id;
                        \App\Classes\Account::CashFlow($loan->customer_key, 'in', 'savings', $remainder, $description);
                    }
                }
            }
        }
        self::LoanCompleted($loan_id);
        return true;
    }

    public static function IsOnLoan($customer_key){
        $loan = \App\loan::where('customer_key', $customer_key)->whereNull('completed')->first();
        if(is_null($loan)){
            return 0;
        }else{
            return $loan->id;
        }
    }

    public static function LoanCompleted($loan_id){
        $loan = \App\loan::find($loan_id);
        $unfinished = \App\loan_schedule::where('loan_id', $loan_id)->where('paid', 0)->get();
        if($unfinished->count()==0){
            if(is_null($loan->completed)){
                \App\loan::where('id', $loan_id)->update(['completed'=> 1]);
                //$loan->update(['completed'=> 1]);
            }
            return true;
        }else{
            return false;
        }
    }


    public static function LoanInstallmentDetails($loan_id){
        $unpaid = \App\loan_schedule::where('loan_id', $loan_id)->where('paid', 0)->orderBy('installment_number', 'ASC')->get();
        if($unpaid->count()==0){
            return false;
        }
        $current = $unpaid[0];
        $amount = $current->amount - $current->paid_amount;
        $object = new \stdClass();
        $object->amount = $amount;
        $object->installment = $current->installment_number;
        $object->pay_by = $current->expected_pay_date;
        return json_encode($object);
    }
}
