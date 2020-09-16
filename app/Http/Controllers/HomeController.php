<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth()->user()->type == 'customer') {
            $registration = \App\registration::where('user_id', Auth()->user()->id)->first();
            if (is_null($registration)) {
                return redirect()->route('member.register');
            }
        }else if(Auth()->user()->type == 'admin'){
            return view('home');
        }else if(Auth()->user()->type == 'staff'){
            return view('home');
        }
        //return view('home');
        $customer = \App\customer::where('key', Auth()->user()->customer_key)->with('loan')->with('cash_flow')->first();

        $cash_flows = $customer->cash_flow->reverse();
        //dd($customer);
        //dd($cash_flow);
        $loanable = json_decode(\App\Classes\Loan::Loanable(Auth()->user()->customer_key, 'business'));
        $share = \App\share_price::orderBy('id', 'DESC')->first();
        return view('dashboards.customer', compact('customer', 'share', 'loanable', 'cash_flows'));
    }
}
