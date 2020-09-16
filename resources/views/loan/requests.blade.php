@extends('layouts.app')

@section('content')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 row">
            <section class = "col-md-4" >
                <div class="card mh-100">
                    <div class="card-header">Loan Requests
                      <a href="{{--{{Route('client.add')}}--}}" class="float-right" title="Add new employee"><i class="fas fa-plus"></i></a></div>
                    <div class="card-body">
                      <input type = "text" name = "search" placeholder = "Search Requests" id = 'employee-keyword' class = "form-control" onkeyup = "SearchEmployees()">
                      <div style="height: 400px; overflow-y: scroll">


                        <div class = "text-center mt-3 d-none" id = 'loader'>
                          <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Searching...</span>
                          </div>
                          <p>Searching...</p>
                        </div>
                        <div id = 'employees' class = "container">
                            <div id="employee-content">
                              @if(isset($requests) && count($requests)>0)
                                @foreach($requests as $request)
                                    <div class = 'row  mt-1 border rounded' onclick = "showLoanRequest({{$request->id}})">
                                        <div class = "col-sm-4" style = "height: 100%">
                                            @if(!is_null($request->user->logo))
                                                <img src = "profiles/{{$request->user->logo}}" class = "img-responsive w-100">
                                            @else
                                                <img src = "{{asset('img/profile-icon.png')}}" class = "img-responsive w-100">
                                            @endif
                                        </div>
                                        <div class = "col-sm-8">
                                            <span><strong><a href = "#emp-{{$request->id}}" id="emp-{{$request->id}}" class = "text-primary">{{$request->user->name}}</a></strong></span><br>
                                            <span><em>{{$request->loan_type->name}}</em></span><br>
                                            <small class = "text-success">
                                                Requested in: {{date("M d, Y", strtotime($request->created_at))}}
                                            </small><br>
                                        </div>

                                    </div>
                                @endforeach
                              @else
                                <div class = "py-3">
                                  <p>There arent any requests</p>
                                </div>
                              @endif
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </section>
            <section class = 'col-md-8' >
                <div class="card ">
                    <div class="card-header">Loan Request Details</div>
                    <div class = " row" style="height: 600px; overflow-y: scroll" id="vue-content">
                        <div class = "text-center col-sm-12 pt-5 mt-2 d-none" id = 'main-loader'>
                          <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Searching...</span>
                          </div>
                          <p>Loading...</p>
                        </div>
                        <div class = "mx-3 px-3 py-3" id = "request-full-content" >
                           <p>There is no information to display please select the registration you would like to view from the registration section</p>


                        </div>
                        <div class = "mx-3 px-3 py-3" v-if="current_loan != '' && approved && !disbursed">
                            <button class="btn btn-success" data-toggle="modal" data-target="#confirmDisbursementModal">Confirm Disbursement</button>
                            <button class="btn btn-danger mx-3">Cancel Loan</button>

                            <!-- Button trigger modal -->
                            {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                Launch demo modal
                            </button>--}}

                            <!-- Modal -->
                            <div  class="modal fade" id="confirmDisbursementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Confirm Disbursement</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="disbursement_date" class="col-12"><strong>Select Disbursement Date</strong>
                                            <input type="date" id="disbursement_date" class="form-control" v-model="disbursement_date">
                                            </label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" v-if="disbursement_date != ''" data-dismiss="modal" class="btn btn-primary" @click="ConfirmDisbursement(disbursement_date)">Confirm</button>
                                            <button type="button" v-else class="btn btn-primary" :data-dismiss="modal" @click="ConfirmDisbursement(disbursement_date)">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "mx-3 px-3 py-3" v-if="(disbursement_date!='') && current_loan!='' && disbursed">
                            <span class="text-success">This loan was disbursed on @{{disbursement_date}}</span>
                        </div>
                        <div class = "mx-3 px-3 py-3" v-if="(approved == 0 || approved == '') && current_loan!=''">
                            <button  class="btn btn-success" v-on:click="LoanApprove()">{{strtoupper(__('registration.approve'))}}</button>
                            <button class="btn btn-danger " >{{strtoupper(__('registration.disapprove'))}}</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@if(request()->session()->has('alert'))
    <script>
      swal("Success!", "{{request()->session()->pull('alert')}}", "success")
    </script>
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    $(document).on('click', '.licence', function(){
        $(this).siblings('.licence-content').slideToggle('slow');
    })
</script>

<script type="text/javascript">
    var vue =  new Vue({
        el: '#vue-content',
        data: {
            approved: 0,
            current_loan: '',
            disbursement_date: '',
            disbursed: false,
            confirm: true
        },
        methods: {
            greet: function(){
                alert(this.approved);
            },
            LoanApprove: function(){
                //alert(id);
                var id = this.current_loan;
                LoanApprove(id)
            },
            ConfirmDisbursement: function(date){
                if(date == ''){
                    alert('Please pick a valid date to continue');
                }else{
                    /*axios.post('{{route("loan.confirm.disbursement")}}', {loan_id: this.current_loan, disburse_date: date}).then(function(response){
                        alert(response.data);
                    })*/
                    var data =  new FormData();
                    data.append('loan_id', this.current_loan);
                    data.append('disburse_date', date);
                    if (window.XMLHttpRequest) {
                        // code for modern browsers
                        xhttp = new XMLHttpRequest();
                    } else {
                        // code for old IE browsers
                        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xhttp.onreadystatechange = function(){
                        /*if(this.readyState >2){
                            alert(this.responseText);
                        }*/
                        if(this.readyState == 4 && this.status == 200){
                            $response = this.responseText;
                            if(this.responseText == 1){
                                vue.disbursement_date = date;
                                vue.disbursed = true;
                            }
                        }
                    }
                    xhttp.open("POST", '{{route("loan.confirm.disbursement")}}', true);
                    xhttp.setRequestHeader('X-CSRF-TOKEN', token);
                    xhttp.send(data);
                }
            }
        }
    })
    /*if(vue.approved == 1){
        alert('approved')
    }else{
        alert(vue.approved)
    }*/

    /*vue.LoanApprove(54);*/
    var token = document.getElementsByTagName('meta')[1].content;
    function SearchEmployees(search = null){
        if(search == null){
          var search = document.getElementById('employee-keyword').value;
        }
        var loader = document.getElementById('loader');
        var content = document.getElementById('employee-content');
        //alert(search);
        var data =  new FormData();
        loader.classList.remove('d-none');
        content.classList.add('d-none');
        data.append('search',search);
        if (window.XMLHttpRequest) {
              // code for modern browsers
              xhttp = new XMLHttpRequest();
           } else {
              // code for old IE browsers
              xhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }

        xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
          content.innerHTML = this.responseText;
          content.classList.remove('d-none');
          loader.classList.add('d-none');
        }

      }
      //alert(xhttp.status)
      xhttp.open("POST", "{{--{{Route('employee.find')}}--}}", true);
      xhttp.setRequestHeader('X-CSRF-TOKEN', token);
      xhttp.send(data);
    }

    function showLoanRequest(id){
        var loader = document.getElementById('main-loader');
        var content = document.getElementById('request-full-content');
        var data =  new FormData();
        loader.classList.remove('d-none');
        content.classList.add('d-none');
        data.append('loan_id',id);

        if (window.XMLHttpRequest) {
          // code for modern browsers
          xhttp = new XMLHttpRequest();
        } else {
          // code for old IE browsers
          xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhttp.onreadystatechange = function(){
            /*if(this.readyState >2){
                alert(this.responseText);
            }*/
        if(this.readyState == 4 && this.status == 200){
            var response = JSON.parse(this.responseText)
          content.innerHTML = response.output;
            if(response.status == null){
                //alert(response.status);
                vue.approved = '';
            }else if(response.status == 1){
                vue.approved = 1;
            }else if(response.status == 0){
                vue.approved = 0;
            }

          vue.current_loan = id;
            vue.disbursement_date = response.disbursed;
            if(response.disbursed!='' && response.disbursed != null){
                vue.disbursed = true;
            }
            //$('#confirmDisbursementModal').modal('hide');
            //alert(response.disbursed);
          content.classList.remove('d-none');
          loader.classList.add('d-none');
        }

      }
      xhttp.open("POST", "{{Route('loan.info')}}", true);
      xhttp.setRequestHeader('X-CSRF-TOKEN', token);
      xhttp.send(data);
    }



    $('#confirmDisbursementModal').modal('show');
    /*$(document).ready(function(){
        alert('hellow there');
    })*/


    function leaveReason(id){
        var reason = document.getElementById('leave-reason-'+id);
        $('#leave-reason-'+id).slideToggle('slow');
        //alert(reason.style.display)
    }

    function showDecision(id){
        $('#decision-'+id).slideToggle('slow');
    }

    function LoanApprove(id){
        axios.post('{{route('loan.approve')}}', {loan_id: id}).then(function(response){
            //alert(response.data)
            if(response.data == 1){
                vue.approved = 1;
            }
        })
    }
    /*showLoanRequest(1);*/
</script>
@endsection
<?php request()->session()->forget('alert')?>
