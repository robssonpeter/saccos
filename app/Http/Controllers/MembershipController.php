<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function RegistrationForm(){

        $user = Auth()->user();
        if($user->type == 'admin' || $user->type == 'staff'){
            return redirect()->route('home');
        }else if($user->type == 'customer'){
            $registration = \App\registration::where('user_id', $user->id)->first();
            if(!is_null($registration)){
                return redirect()->route('home');
            }
        }
        return view('registration', compact('user'));
    }


    public function RegistrationFormSave(){
        //dd(\request()->all());

        $data = \request()->all();
        $data['user_id'] = Auth()->User()->id;
        $data['heir_relation'] = \request()->relation;
        /*$data['confirmed'] = 0;*/
        //dd($data);
        $registration = \App\registration::create($data);
        $user = \App\User::where('id', Auth()->user()->id)->update(['registration_id'=>$registration->id]);
        return view('notification');
    }
    public function Notification(){
        return view("notification");
    }

    public function Registrations(){
        $registrations = \App\registration::orderBy('id', 'DESC')->get();
        return view("registrations.all", compact('registrations'));
    }

    public function RegistrationInformation(){
        $regid = \request()->regid;
        $registration = \App\registration::find($regid);
        $sponsors = new Collection();
        $sponsorOutput = "";
        $referees = [];
        if($registration->ref_1_id){
            array_push($referees, $registration->ref_1_id);
        }
        if($registration->ref_2_id){
            array_push($referees, $registration->ref_2_id);
        }
        if(count($referees)>1){
            $sponsors = \App\User::where('customer_key', $referees[0])->orWhere('customer_key', $referees[1])->with('registrations')->get();
        }else if(count($referees)==1){
            $sponsors = \App\User::where('customer_key', $referees[0])->with('registrations')->get();
        }

        $i = 1;
        if($sponsors->count()>0){
            foreach ($sponsors as $sponsor){
                $sponsorOutput .= '<div><h4>Referee '.$i.':</h4>
                                        <div class="row">
                                            <label for="Full Name" class="col-md-6"><strong>'.__('registration.fullname').':</strong> '.$sponsor->name.'</label><br>
                                                <label for="" class="col-md-6"><strong>'.__('registration.member_id_1').':</strong> '.$sponsor->customer_key.'</label><br>
                                                <label for="" class="col-md-6"><strong>'.__('registration.house_number').':</strong> '.$sponsor->registrations->house_number.'</label><br>
                                                <label for="" class="col-md-6"><strong>'.__('registration.address').'</strong> '.$sponsor->registrations->adress.'</label><br>
                                                <label for="" class="col-md-6"><strong>'.__('registration.phone_number').':</strong> '.$sponsor->registrations->phone.'</label><br>
                                        </div>
                                    </div>';
                $i++;
            }
        }else{
            $sponsorOutput .= "<div class='py-3'><p>".__('registration.no_referees')."</p></div>";
        }

        $output = '<div class="container">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Attachments</a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="panel">
                                            <div class="panel-header"><h3><strong>Personal detail</strong></h3></div>
                                            <div class="panel-body pl-3 row">
                                                <label for="Full Name" class="col-md-6"><strong>'.__('registration.fullname').':</strong> '.$registration->name.'</label><br>
                                                    <label for="Date" class="col-md-6"><strong>'.__('registration.date_of_birth').':</strong> '.$registration->birth_date.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.occupation').':</strong> '.$registration->occupation.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.address').':</strong> '.$registration->adress.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.house_number').':</strong> '.$registration->name.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.phone_number').':</strong> '.$registration->phone.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.sub_parish').':</strong> '.$registration->sub_parish.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.congregation').':</strong> '.$registration->congregation.'</label><br>
                                            </div>


                                            <div class="panel-header"><h3><strong>'.__('registration.details_of_heir').'</strong></h3></div>
                                            <div class="panel-body pl-3 row">
                                                <label class="col-md-6"><strong>'.__('registration.fullname').':</strong> '.$registration->heir_full_name.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.relation').':</strong> '.$registration->heir_relation.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.address').':</strong> '.$registration->heir_house_number.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.house_number').':</strong> '.$registration->heir_house_number.'</label><br>
                                                    <label for="" class="col-md-6"><strong>'.__('registration.phone_number').':</strong> '.$registration->heir_house_number.'</label><br>
                                            </div>


                                            <div class="panel-header"><h3><strong>'.__('registration.referees').'</strong></h3></div>
                                            <div class="panel-body pl-3">
                                                '.$sponsorOutput.'
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <a href="">Letter from Chairman of congregation</a><br>
                                        <a href="">certificate of baptism</a>
                                    </div>
                                </div>
                                
                    </div>';
        $return = [
            "output"=>$output,
            "status"=>$registration->confirmed
        ];

        return json_encode($return);
    }

    public function RegistrationApprove(Hashids $hashids){
        $regid = \request('regid');
        $user = \App\User::where('registration_id', $regid)->first();
        $registration = \App\registration::find($regid);
        $customer = \App\customer::where('registration_id', $registration->id)->first();

        if(!is_null($user)){

            $customer_key = strtoupper($hashids->encode($regid, date('s',time())));
            if(is_null($user->customer_key)){
                $approveUser = \App\User::where('id', $registration->user_id)->update(['registration_id'=>$regid, 'customer_key'=>$customer_key]);
                $approved = \App\registration::where('id', $regid)->update(['confirmed'=>1, 'customer_id'=>$customer_key]);
            }

            if(is_null($customer)){
                $customer = \App\customer::create([
                    'key' => $customer_key,
                    'registration_id' => $regid,
                    'user_id' => $user->id,
                    'deposits_balance' => 0,
                    'shares' => 0,
                    'savings_balance' => 0,
                    'status' => 'active',
                ]);
            }
        }else{
            $customer_key = strtoupper($hashids->encode($regid, date('s',time())));
            $approved = \App\registration::where('id', $regid)->update(['confirmed'=>1]);
            $approveUser = \App\User::where('id', $registration->user_id)->update(['registration_id'=>$regid, 'customer_key'=>$customer_key]);
            $customer = \App\customer::create([
                'key' => $customer_key,
                'registration_id' => $regid,
                'user_id' => $user->id,
                'deposits_balance' => 0,
                'shares' => 0,
                'savings_balance' => 0,
                'status' => 'active',
            ]);
        }


        if($approved){
            return 1;
        }else{
            return 0;
        }
    }

    public function RefereeCheck(){
        $key = \request('key');
        $customer = \App\User::where('customer_key', $key)->first();
        if(is_null($customer)){
            $return = [
              'status' => 0,
              'message' => "No customer with that key",
            ];
            return json_encode($return);
        }else{
            $return = [
              'status'=>1,
              'message'=> ''
            ];
            return json_encode($return);
        }
    }

    public function RegistrationSearch(){
        $keyword = request()->all()['search'];
        $output = "";
        $data = DB::table('registrations')
            ->where('name', 'like', '%'.$keyword.'%')
            ->orWhere('occupation', 'like', '%'.$keyword.'%')
            ->orWhere('phone', 'like', '%'.$keyword.'%')
            ->orWhere('house_number', 'like', '%'.$keyword.'%')
            ->orWhere('customer_id', 'like', '%'.$keyword.'%')
            ->orWhere('heir_full_name', 'like', '%'.$keyword.'%')
            /*->join('contracts', 'contracts.id','employees.ContractID')
            ->select('employees.*', 'contracts.expiry')*/
            ->limit(50)
            ->get();
        //echo json_encode($data);
        foreach($data as $empinfo){
            $output .= "<div class = 'row  mt-1 border rounded' onclick = 'showRegistration(".$empinfo->id.")'>
                <div class = 'col-sm-4' style = 'height: 100%'>";


            $output .= '<img src = "img/profile-icon.png" class = "img-responsive w-100">';


            $output .= '</div>
                <div class = "col-sm-8">
                    <span><strong><a href = "#emp-'.$empinfo->id.'" class = "text-primary">'.$empinfo->name.'</a></strong></span><br>
                    <span><em>'.$empinfo->occupation.'</em></span><br>
                    <small class = "text-success">
                        created in: '.date("M d, Y", strtotime($empinfo->created_at)).'
                    </small><br>
                </div>
            </div>';



        }

        if($data->count()<1){
            echo "<div class = 'mt-3'>
                     <p>No result found</p>
                  </div>";
        }
        echo $output;
    }

}
