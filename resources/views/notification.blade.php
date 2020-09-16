@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-body h4">Notification</div>

                <div class="card-body">
                    <p> Your application is submitted. Wait for the approval from the Authority. you will be notified as soon as your approval is successful</p>
                    <p> Would you like to continue <a href="{{route("home")}}">CONTINUE</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
