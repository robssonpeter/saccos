@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 row">
            <section class = "col-md-4" >
                <div class="card mh-100">
                    <div class="card-header">Registration
                      <a href="{{--{{Route('client.add')}}--}}" class="float-right" title="Add new employee"><i class="fas fa-plus"></i></a></div>
                    <div class="card-body">
                      <input type = "text" name = "search" placeholder = "Search Registrations" id = 'employee-keyword' class = "form-control" onkeyup = "SearchEmployees()">
                      <div style="height: 400px; overflow-y: scroll">


                        <div class = "text-center mt-3 d-none" id = 'loader'>
                          <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Searching...</span>
                          </div>
                          <p>Searching...</p>
                        </div>
                        <div id = 'employees' class = "container">
                            <div id="employee-content">
                              @if(isset($registrations) && count($registrations )>0)
                                @foreach($registrations ?? '' as $registration)
                                    <div class = 'row  mt-1 border rounded' onclick = "showRegistration({{$registration->id}})">
                                        <div class = "col-sm-4" style = "height: 100%">
                                            @if(!is_null($registration->logo))
                                                <img src = "profiles/{{$registration->logo}}" class = "img-responsive w-100">
                                            @else
                                                <img src = "img/profile-icon.png" class = "img-responsive w-100">
                                            @endif
                                        </div>
                                        <div class = "col-sm-8">
                                            <span><strong><a href = "#emp-{{$registration->id}}" id="emp-{{$registration->id}}" class = "text-primary">{{$registration->name}}</a></strong></span><br>
                                            <span><em>{{$registration->occupation}}</em></span><br>
                                            <small class = "text-success">
                                                Created in: {{date("M d, Y", strtotime($registration->created_at))}}
                                            </small><br>
                                        </div>

                                    </div>
                                @endforeach
                              @else
                                <div class = "py-3">
                                  <p>There arent registrations</p>
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
                    <div class="card-header">Registration Details</div>
                    <div class = " row" style="height: 600px; overflow-y: scroll" id="vue-content">
                        <div class = "text-center col-sm-12 pt-5 mt-2 d-none" id = 'main-loader'>
                          <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Searching...</span>
                          </div>
                          <p>Loading...</p>
                        </div>
                        <div class = "mx-3 px-3 py-3" id = "registration-full-content" >
                           <p>There is no information to display please select the registration you would like to view from the registration section</p>


                        </div>
                        <div class = "mx-3 px-3 py-3" v-if="current_registration != '' && approved">
                            <span class="text-success"><strong>This form is already approved</strong></span>
                        </div>
                        <div class = "mx-3 px-3 py-3" v-if="(approved == 0 || approved == '') && current_registration!=''">
                            <button  class="btn btn-success" v-on:click="registrationApprove()">{{strtoupper(__('registration.approve'))}}</button>
                            <button class="btn btn-danger" >{{strtoupper(__('registration.disapprove'))}}</button>
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
            current_registration: '',
        },
        methods: {
            greet: function(){
                alert(this.approved);
            },
            registrationApprove: function(){
                //alert(id);
                var id = this.current_registration;
                RegistrationApprove(id)
            }
        }
    })
    /*if(vue.approved == 1){
        alert('approved')
    }else{
        alert(vue.approved)
    }*/

    /*vue.registrationApprove(54);*/
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
            /*if(this.readyState >2){
                alert(this.responseText)
            }*/
        if(this.readyState == 4 && this.status == 200){
          content.innerHTML = this.responseText;
          content.classList.remove('d-none');
          loader.classList.add('d-none');
        }

      }
      //alert(xhttp.status)
      xhttp.open("POST", "{{Route('registration.find')}}", true);
      xhttp.setRequestHeader('X-CSRF-TOKEN', token);
      xhttp.send(data);
    }

    function showRegistration(id){
        var loader = document.getElementById('main-loader');
        var content = document.getElementById('registration-full-content');
        var data =  new FormData();
        loader.classList.remove('d-none');
        content.classList.add('d-none');
        data.append('regid',id);

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

          vue.current_registration = id;
          content.classList.remove('d-none');
          loader.classList.add('d-none');
        }

      }
      xhttp.open("POST", "{{Route('registration.info')}}", true);
      xhttp.setRequestHeader('X-CSRF-TOKEN', token);
      xhttp.send(data);
    }





    function leaveReason(id){
        var reason = document.getElementById('leave-reason-'+id);
        $('#leave-reason-'+id).slideToggle('slow');
        //alert(reason.style.display)
    }

    function showDecision(id){
        $('#decision-'+id).slideToggle('slow');
    }

    function RegistrationApprove(id){
        axios.post('{{route('registration.approve')}}', {regid: id}).then(function(response){
            if(response.data == 1){
                vue.approved = 1;
            }
        })
    }
    /*showRegistration(1);*/
</script>
@endsection
<?php request()->session()->forget('alert')?>
