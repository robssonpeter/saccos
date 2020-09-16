<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class LoanController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function LoanRequest(){
        if(Auth()->user()->customer_key){
            return view('loan.request');
        }else{
            abort(401);
        }

    }

    public function LoanRequests(){
        $requests = \App\loan::orderBy('id', 'DESC')->with('customer', 'user', 'loan_type')->get();
        //dd($requests);
        return view('loan.requests', compact('requests'));
    }

    public function Loantypes(){
        $types = \App\loan_type::all();
        $output = [];
        $rates = [];
        foreach ($types as $type){
            array_push($output, [
                'name'=>$type->name,
                'code'=>$type->code,
                'rate'=>$type->interest,
                'max_amount'=>$type->max_amount,
                'max_months'=>$type->max_recovery_months
            ]);
            $rates[$type->code] = $type->interest;
        }
        $final = [
            'output' => $output,
            'rates' => $rates,
        ];
        return json_encode($final);
    }

    public function LoanMaxAmount(){
        $type = \request()->loan;
        $loanable = \App\Classes\Loan::Loanable(Auth()->user()->customer_key, $type);
        return $loanable;
    }

    public function LoanRequestSubmit(){
        $data = \request()->all();
        $loan = \App\loan_type::where('code', \request()->type)->first();
        $data['recovery_months'] = \request()->months_of_repayment;
        $data['rate'] = $loan->interest;
        $data['customer_key'] = Auth()->user()->customer_key;
        if(\App\loan::create($data)){
            return redirect()->route('home');
        }
    }

    public function LoanInfo(){
        $loan = \App\loan::where('id', request()->loan_id)->with('customer', 'user', 'loan_type')->first();
        $schedule = \App\loan_schedule::where('loan_id', $loan->id)->orderBy('installment_number', 'ASC')->get();
        $scheduleOutput = '';
        if(is_null($loan->disbursement_date)){
            $scheduleOutput .= "<p>The schedule will be visible once the loan has been disbursed to the customer</p>";
        }else{
            $scheduleOutput .= '
                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td><strong>S/N</strong></td>
                                        <td><strong>Expected Pay Date</strong></td>
                                        <td><strong>Opening Balance</strong></td>
                                        <td><strong>Interest</strong></td>
                                        <td><strong>Monthly Recovery</strong></td>
                                        <td><strong>Principle</strong></td>
                                        <td><strong>Closing Balance</strong></td>
                                        <td><strong>Paid</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
            ';
            foreach($schedule as $item){
                $scheduleOutput .= '
                    <tr>
                        <td>'.$item->installment_number.'</td>
                        <td>'.date('M, d, Y', strtotime($item->expected_pay_date)).'</td>
                        <td>'.number_format($item->opening_balance, 2).'</td>
                        <td>'.number_format($item->interest, 2).'</td>
                        <td>'.number_format($item->amount, 2).'</td>
                        <td>'.number_format(($item->amount-$item->interest), 2).'</td>
                        <td>'.number_format($item->closing_balance, 2).'</td>
                        <td>'.$item->paid.'</td>
                    </tr>
                ';
            }
            $scheduleOutput .= '
                </tbody>
            </table>
            ';
        }
        $registration = \App\registration::find($loan->customer->registration_id);
        $output = '<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Loan Details</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Loan Schedule</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Attachments</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div>
                            <h3>Personal info</h3>
                            <section class="row">
                                <span class="col-6"><strong>Name:</strong> '.$registration->name.'</span>
                                <span class="col-6"><strong>Occupation:</strong> '.$registration->occupation.'</span>
                                <span class="col-4"><strong>Address:</strong> '.$registration->adress.'</span>
                                <span class="col-4"><strong>House Number:</strong> '.$registration->house_number.'</span>
                                <span class="col-4"><strong>Phone:</strong> '.$registration->phone.'</span>
                                <span class="col-4"><strong>Sub-Parish:</strong> '.$registration->sub_parish.'</span>
                                <span class="col-4"><strong>Zone:</strong> '.$registration->zone.'</span>
                                <span class="col-4"><strong>Congregation:</strong> '.$registration->congregation.'</span>
                                  
                            </section>
                            <hr>
                            <h3>Loan Info</h3>
                            <section class="row">
                                <span class="col-4"><strong>Loan Type:</strong> '.$loan->loan_type->name.'</span>
                                <span class="col-4"><strong>Loan Amount:</strong> '.number_format($loan->amount).'</span>
                                <span class="col-4"><strong>Recovery Months:</strong> '.$loan->recovery_months.'</span>
                                <span class="col-4 mt-3"><strong>Current Savings Balance:</strong> '.number_format($loan->customer->savings_balance).'</span>
                                <span class="col-4 mt-3"><strong>Current Deposits Balance:</strong> <span class="col-12">'.number_format($loan->customer->deposits_balance).'</span></span>
                                <span class="col-4 row mt-3"><strong class="col-12">Current Shares:</strong> <span class="col-12">'.number_format($loan->customer->shares).'</span></span>
                            </section>
                            <hr>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        '.$scheduleOutput.'
                      </div>
                      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                    </div>';
        if(!is_null($loan->disbursement_date)){
            $disbursed = date('M, d Y', strtotime($loan->disbursement_date));
        }else{
            $disbursed = '';
        }
        $return = [
            'output'=> $output,
            'status'=> $loan->accepted,
            'disbursed' => $disbursed
        ];
        return json_encode($return);
    }

    public function Approve(){
        $loan_id = \request()->loan_id;
        if(\App\loan::where('id', $loan_id)->update(['accepted'=>1])){
            return 1;
        }else{
            return 0;
        }
    }

    public function ConfirmDisbursement(){
        $disburse_date = \request()->disburse_date;
        $loan_id = request()->loan_id;
        $loan = \App\loan::find($loan_id);
        $optimized = [];
        if(\App\loan::where('id', $loan_id)->update(['disbursement_date'=>$disburse_date, 'disbursement_method'=>null])){
            $schedule = \App\Classes\Loan::PaymentSchedule($loan->amount, $loan->rate/100, $loan->recovery_months);
            $optimized = [];
            foreach($schedule as $installment){
                $pay_turn = [
                    'loan_id'=> $loan_id,
                    'customer_id' => $loan->customer_key,
                    'expected_pay_date' => $installment['expected_pay_date'],
                    'amount' => $installment['monthly_payment'],
                    'paid_amount' => 0,
                    'interest' => $installment['interest'],
                    'paid' => 0,
                    'opening_balance' => $installment['opening_balance'],
                    'installment_number' => $installment['current_month'],
                    'closing_balance' => $installment['closing_balance']
                ];
                array_push($optimized, $pay_turn);
            }
            //\App\loan_schedule::createMany($optimized);
            $loan->loan_schedule()->createMany($optimized);
            return 1;
        }else{
            return 0;
        }
    }

    public function RepaymentLogger(){
        return view('loan.repayment.logging');
    }

    public function OwingCustomers(){
        $customers = \App\loan::whereNotNull('disbursement_date')->whereNull('completed')->with('registration')->get();
        $return = [];
        $object = [];
        foreach($customers as $customer){
            array_push($return, ['name' => $customer->registration->name, 'key'=>$customer->customer_key]);
            $object[$customer->customer_key] = $customer->registration->name;
        }
        $output = [
            'return' => $return,
            'object' => $object
        ];
        return json_encode($output);
    }

    public function CustomerOwingLoan(){
        $customer_key = \request()->customer;
        $loan = \App\loan::where('customer_key', $customer_key)->whereNull('completed')->first();
        return json_encode($loan);
    }

    public function RepaymentLogSave(){
        $data = json_decode(\request()->data);
        foreach($data as $datum){
            $loan_id = $datum->loan_ID;
            $amount = $datum->amount;
            \App\Classes\Loan::Repayment($loan_id, $amount);
        }
        return 1;
    }

    public function InstallmentDetails(){
        $loan_id = \request()->loan_id;
        $installment = \App\Classes\Loan::LoanInstallmentDetails($loan_id);
        return $installment;
    }
}
