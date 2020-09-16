@extends('layouts.app')

@section('content')
<div class="container" id = "vue">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white border-0 shadow-sm rounded">
                <div class="card-header bg-white border-0">
                    {{ __('withdrawal_request.request_withdrawal') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('withdraw.request.submit')}}">

                        @csrf

                        @if(isset($type) && $type->id)
                            <input type="hidden" name="id" id="id" value="{{ $type->id }}">
                            {{--@method('UPDATE')--}}
                        @endif

                        <div class="form-group">
                            <label for="name">{{ __('withdrawal_request.account_type') }}</label>
                            <select name="type" class="form-control" id="" v-model="WithdrawType" v-on:change="LoanRateControl()">
                                <option value="">{{ __('loan_request.select') }}</option>
                                <option  v-for="AccountType in AccountTypes" :value="AccountType.name" >@{{AccountType.name}}</option>
                            </select>

                            @if ($errors->has('type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>
                        {{--<span v-if="WithdrawType!=''"><strong>Loan Rate:</strong> <span v-text="LoanRate"></span>%</span>--}}

                        <div class="form-group">
                            <label for="amount">{{ __('withdrawal_request.amount') }}</label>

                            <input id="amount" type="number"
                                   class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                   name="amount"
                                    v-model="WithdrawAmount"
                                   value="{{ (isset($amount) ? $amount->amount : old('amount')) }}"
                                   :max="WithdrawMaxAmount"
                                   required autofocus>
                            <span >
                                <strong class="text-success" v-if="WithdrawType!='' && WithdrawAmount<=WithdrawMaxAmount">{{__('withdrawal_request.withdraw_up_to')}}  <span>Tshs. @{{WithdrawMaxAmount}}</span></strong>
                                <strong class="text-danger" v-if="WithdrawAmount>WithdrawMaxAmount">{{__('withdrawal_request.withdraw_up_to')}} <span>Tshs. @{{WithdrawMaxAmount}}</span></strong>
                            </span>
                            @if ($errors->has('amount'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button v-if="WithdrawAmount<=WithdrawMaxAmount" type="submit" class="btn btn-primary float-right">
                                    {{ __('loan_request.send_request') }}
                                </button>
                                <button v-else type="submit" class="btn btn-primary float-right" disabled>
                                    {{ __('loan_request.send_request') }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <a href="{{--{{ route('payroll.settings') }}--}}" class="btn border-primary">Back</a>
        </div>
    </div>
</div>
<script>
    var vue = new Vue({
        el: "#vue",
        data: {
            AccountTypes: [],
            WithdrawType: '',
            WithdrawAmount: 0,
            WithdrawMaxAmount: 0,
        },
        methods: {
            AccountTypeControl: function(){
                axios.post("{{route('account.types')}}").then(function(response){
                    vue.AccountTypes = response.data.output;
                    /*vue.WithdrawTypes = response.data.output;
                    vue.LoanRates = response.data.rates;*/
                    //alert(response.data)
                })
            },
            LoanRateControl: function(){
                if(this.WithdrawType==""){
                    this.WithdrawMaxAmount = 0;
                    this.LoanRecoveryMonths = 0;
                }
                this.WithdrawAmountControl();
            },
            WithdrawAmountControl: function(){
                axios.post("{{route('account.max-amount')}}", {account: vue.WithdrawType}).then(function(response){
                    vue.WithdrawMaxAmount = response.data.max_amount;
                    //alert(response.data)
                })
            }
        }
    })
    vue.AccountTypeControl();
</script>
@endsection
