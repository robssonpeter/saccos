@extends('layouts.app')

@section('content')
<div class="container" id = "vue">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white border-0 shadow-sm rounded">
                <div class="card-header bg-white border-0">
                    {{ __('loan_request.request_loan') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('loan.request.submit')}}">

                        @csrf

                        @if(isset($type) && $type->id)
                            <input type="hidden" name="id" id="id" value="{{ $type->id }}">
                            {{--@method('UPDATE')--}}
                        @endif

                        <div class="form-group">
                            <label for="name">{{ __('loan_request.loan_type') }}</label>
                            <select name="type" class="form-control" id="" v-model="LoanType" v-on:change="LoanRateControl()">
                                <option value="">{{ __('loan_request.select') }}</option>
                                <option  v-for="LoanType in LoanTypes" :value="LoanType.code" >@{{LoanType.name}}</option>
                            </select>

                            @if ($errors->has('type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>
                        <span v-if="LoanType!=''"><strong>Loan Rate:</strong> <span v-text="LoanRate"></span>%</span>

                        <div class="form-group">
                            <label for="amount">{{ __('loan_request.amount') }}</label>

                            <input id="amount" type="number"
                                   class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                   name="amount"
                                    v-model="LoanAmount"
                                   value="{{ (isset($amount) ? $amount->amount : old('amount')) }}"
                                   :max="LoanMaxAmount"
                                   required autofocus>
                            <span >
                                <strong class="text-success" v-if="LoanType!='' && LoanAmount<=LoanMaxAmount">Borrow up to  <span>Tshs. @{{LoanMaxAmount}}</span></strong>
                                <strong class="text-danger" v-if="LoanAmount>LoanMaxAmount">Borrow up to  <span>Tshs. @{{LoanMaxAmount}}</span></strong>
                            </span>
                            @if ($errors->has('amount'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="months_of_repayment">{{ __('loan_request.months_of_repayment') }}</label>

                            <input id="months_of_repayment"
                                   class="form-control{{ $errors->has('months_of_repayment') ? ' is-invalid' : '' }}"
                                   name="months_of_repayment"
                                    v-model="LoanMonths"
                                   :max="LoanRecoveryMonths"
                                   required autofocus>
                            <span>
                                <strong class="text-success" v-if="LoanType!='' && LoanMonths<=LoanRecoveryMonths">{{__('loan_request.maximum')}}:  <span>@{{LoanRecoveryMonths}}</span></strong>
                                <strong class="text-danger" v-if="LoanMonths>LoanRecoveryMonths">{{__('loan_request.maximum')}}:  <span>@{{LoanRecoveryMonths}}</span></strong>
                            </span>
                            @if ($errors->has('months_of_repayment'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('months_of_repayment') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="">
                            <label class="form-check-label" for="accept_fees">
                                <input type="checkbox" name="accept_fees" class="" id="accept_fees" required> {{ __('loan_request.agree_loan_fees') }} <span v-if="LoanAmount>0">(<strong>Amount:</strong> <span v-text="(LoanFeeRate/100)*LoanAmount"></span>)</span>
                            </label>
                        </div>

                        <div class="">
                            <label class="form-check-label" for="accept_insurance">
                                <input type="checkbox" name="agree_insurance" class="" id="accept_insurance" required> {{ __('loan_request.agree_insurance_fees') }} <span v-if="LoanAmount>0 && LoanMonths>0">(<strong>Amount:</strong> <span v-text="(InsuranceFeeRate/100)*LoanMonths*LoanAmount"></span>)</span>
                            </label>
                        </div>

                        <div class="">
                            <label class="form-check-label" for="accept_penalty">
                                <input type="checkbox" name="agree_penalty" class="" id="accept_penalty" required> {{ __('loan_request.agree_penalty') }}
                            </label>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button v-if="LoanAmount<=LoanMaxAmount && LoanMonths<=LoanRecoveryMonths" type="submit" class="btn btn-primary float-right">
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
            LoanTypes: [],
            LoanType: '',
            LoanAmount: 0,
            LoanMonths: 0,
            LoanRate: 0,
            LoanMaxAmount: 0,
            LoanRecoveryMonths: 0,
            LoanFeeRate: 0,
            InsuranceFeeRate: 0,
            LoanRates: [

            ]
        },
        methods: {
            LoanTypeControl: function(){
                axios.post("{{route('loan.types')}}").then(function(response){
                    vue.LoanTypes = response.data.output;
                    vue.LoanRates = response.data.rates;
                })
            },
            LoanRateControl: function(){
                if(this.LoanType==""){
                    this.LoanMaxAmount = 0;
                    this.LoanRecoveryMonths = 0;
                }
                this.LoanRate = this.LoanRates[this.LoanType]
                this.LoanAmountControl();
            },
            LoanAmountControl: function(){
                axios.post("{{route('loan.max-amount')}}", {loan: vue.LoanType}).then(function(response){
                    vue.LoanMaxAmount = response.data.loanable;
                    vue.LoanRecoveryMonths = response.data.months;
                    vue.LoanFeeRate = response.data.loan_fee;
                    vue.InsuranceFeeRate = response.data.insurance_fee;
                })
            }
        }
    })
    vue.LoanTypeControl();
</script>
@endsection
