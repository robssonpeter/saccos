@extends('layouts.app')

@section('content')

<div class="container-fluid px-5">

    <div class="container-fluid">
        <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-3 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.shares')}}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="{{--{{Route('employees.all')}}--}}">All Employees</a>
                                <a class="dropdown-item" href="{{--{{Route('attendance')}}--}}">Attendance</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{--{{Route('employee.create')}}--}}">Add new employee</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- <div class="chart-area">
                          <canvas id="myAreaChart"></canvas>
                        </div> -->
                        <p><strong>{{__('dashboard.count')}}: </strong>{{number_format($customer->shares)}}</p>
                        <p><strong>{{__('dashboard.unit_price')}}: </strong>{{number_format($share->amount)}}</p>
                        <p><strong>{{__('dashboard.value')}}: </strong>{{number_format($share->amount*$customer->shares)}}</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-10">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.savings')}}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="{{--{{Route('payrolls')}}--}}">Manage</a>
                                <a class="dropdown-item" href="{{--{{Route('payroll.create')}}--}}">New Payroll</a>

                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- <div class="chart-area">
                          <canvas id="myAreaChart"></canvas>
                        </div> -->
                        <div class = "row">
                            <section class = "col-sm-6">
                                <p class = ""><strong>{{__('dashboard.current_balance')}}: </strong>{{number_format($customer->savings_balance)}}</p>
                                <p><strong>{{__('dashboard.loanable_amount')}}: </strong>{{number_format($loanable->loanable)}}
                                <p><strong>{{__('dashboard.last_loan')}}: </strong>{{$customer->loan->count()==0?'none':$customer->loan[0]->amount}}</p>
                            </section>
                            <section class = "col-sm-6">
                                {{--<p><strong>Last PAYE: </strong>
                                    --}}{{--{{$currency}}. {{
                          number_format(\App\Classes\Payment::Payroll_paye(
                            App\Payroll::count()>0 ? App\Payroll::orderBy('id', 'DESC')->first()->id:0, 2)
                          )
                        }}--}}{{--
                                </p>
                                <p><strong>Last Issued Payslips: </strong>
                                    --}}{{--{{
                                      App\Payroll::count()>0 ? App\Payslip::where('payroll_id', App\Payroll::orderBy('id', 'DESC')->first()->id)->count():0
                                   }}--}}{{--
                                </p>
                            </section>--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.deposits')}}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="#" id = "show-requests" data-toggle="modal" data-target="#manageRequestModal" onclick="loadLeaveRequests()">Show Requests</a>

                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- <div class="chart-area">
                          <canvas id="myAreaChart"></canvas>
                        </div> -->
                        <p><strong>{{__('dashboard.current_balance')}}: </strong>{{number_format($customer->deposits_balance)}}</p>
                        <p><strong>{{__('dashboard.total_deposits')}}: </strong>{{--{{\App\Leave_request::all()->count()}}--}}</p>
                        <p><strong>{{__('dashboard.last_deposit')}}: </strong>{{--{{\App\Leave_request::all()->count()}}--}}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.cash_flow')}}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="{{--{{Route('payroll.settings')}}--}}">Settings</a>

                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="max-height: 350px; overflow-y: scroll">

                            <!-- <canvas id="myAreaChart"></canvas> -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td><strong>{{__('dashboard.transaction_id')}}</strong></td>
                                        <td><strong>{{__('dashboard.date')}}</strong></td>
                                        <td><strong>{{__('dashboard.transaction_type')}}</strong></td>
                                        <td><strong>{{__('dashboard.description')}}</strong></td>
                                        <td><strong>{{__('dashboard.amount')}}</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cash_flows as $cash_flow)
                                    <tr>
                                        <td>{{$cash_flow->id}}</td>
                                        <td>{{$cash_flow->type."(".$cash_flow->nature.")"}}</td>
                                        <td>{{$cash_flow->type."(".$cash_flow->nature.")"}}</td>
                                        <td>{{is_null($cash_flow->description)?'none': $cash_flow->description}}</td>
                                        @if($cash_flow->nature == 'in')
                                            <td class="text-success">{{number_format($cash_flow->amount)}}</td>
                                        @else
                                            <td class="text-danger">{{number_format($cash_flow->amount)}}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{--<div class="spinner-border text-primary align-middle mt-5" role="status" id = 'earning-loader'>
                                <span class="sr-only">Loading...</span>
                            </div>--}}
                            <!--  <canvas id="EarningOverview"></canvas> -->

                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.loan_overview')}}</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="{{Route('loan.request')}}">{{__('dashboard.request_loan')}}</a>

                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="max-height: 350px; overflow-y: scroll">
                        <div class="pie-chart-area text-center align-middle" id = "deduction-area" >
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td><strong>{{__('dashboard.recovery_months')}}</strong></td>
                                        <td><strong>{{__('dashboard.opening_balance')}}</strong></td>
                                        <td><strong>{{__('dashboard.interest')}}</strong></td>
                                        <td><strong>{{__('dashboard.monthly_recovery')}}</strong></td>
                                        <td><strong>{{__('dashboard.principle')}}</strong></td>
                                        <td><strong>{{__('dashboard.closing_balance')}}</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>1000000</td>
                                        <td>15000</td>
                                        <td>172913.25</td>
                                        <td>157931.25</td>
                                        <td>842068.75</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <canvas id="myAreaChart"></canvas> -->
                            {{--<div class="spinner-border text-primary align-middle mt-5" role="status" id = 'earning-loader'>
                                <span class="sr-only">Loading...</span>
                            </div>--}}
                            <!--  <canvas id="EarningOverview"></canvas> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
