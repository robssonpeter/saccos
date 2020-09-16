@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">{{ __('registration.registration.form.fill') }}</div>

                <div class="card-body" id="vue">
                    <form method="POST" action="{{ route('member.register.save') }}">
                        @csrf
                        <!--Beggining of part a-->
                        {{--Personal Line--}}
                        <h4>{{__('registration.personal_information')}}</h4>
                        <section class="row">
                            <div class="form-group col-md-4">
                                <label for="name"  class="col-form-label">{{ __('registration.fullname') }}</label>

                                <div>
                                    <input id="name" type="text" placeholder="{{ __('registration.fullname') }}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name')?old('name'):$user->name }}"  autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="birth_date" class="col-form-label">{{ __('registration.date_of_birth') }}</label>

                                <div>
                                    <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}"  autocomplete="birth_date">

                                    @error('birth_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="occupation" class="col-form-label ">{{ __('registration.occupation') }}</label>

                                <div>
                                    <input id="occupation" placeholder="{{__('registration.occupation.placeholder')}}" type="text" class="form-control @error('occupation') is-invalid @enderror" name="occupation" value="{{ old('occupation') }}"  autocomplete="occupation">

                                    @error('occupation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{--Location Line--}}
                        <section class="row">
                            <div class="form-group col-md-4">
                                <label for="adress"  class="col-form-label">{{ __('registration.address') }}</label>

                                <div>
                                    <input id="adress" type="text" placeholder="{{ __('registration.address.placeholder')}}" class="form-control @error('adress') is-invalid @enderror" name="adress" value="{{ old('adress') }}"  autocomplete="adress" autofocus>

                                    @error('adress')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="house_number" class="col-form-label">{{ __('registration.house_number') }}</label>

                                <div>
                                    <input id="house_number" type="text" class="form-control @error('house_number') is-invalid @enderror" name="house_number" value="{{ old('house_number') }}"  autocomplete="house_number">

                                    @error('house_number')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="phone" class="col-form-label ">{{ __('registration.phone_number') }}</label>

                                <div>
                                    <input id="phone" placeholder="{{__('registration.phone_number.placeholder')}}" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone')?old('phone'):$user->phone }}"  autocomplete="phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{--Jumuiya Line--}}
                        <section class="row">
                            <div class="form-group col-md-4">
                                <label for="sub_parish"  class="col-form-label">{{ __('registration.sub_parish') }}</label>

                                <div>
                                    <input id="sub_parish" type="text" placeholder="{{ __('registration.sub_parish.placeholder')}}" class="form-control @error('sub_parish') is-invalid @enderror" name="sub_parish" value="{{ old('sub_parish') }}"  autocomplete="sub_parish" autofocus>

                                    @error('sub_parish')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="zone" class="col-form-label">{{ __('registration.zone') }}</label>

                                <div>
                                    <input id="zone" placeholder="{{__('registration.zone.placeholder')}}" type="text" class="form-control @error('zone') is-invalid @enderror" name="zone" value="{{ old('zone') }}"  autocomplete="zone">

                                    @error('zone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="congregation" class="col-form-label ">{{ __('registration.congregation') }}</label>

                                <div>
                                    <input id="congregation" placeholder="{{__('registration.congregation.placeholder')}}" type="text" class="form-control @error('congregation') is-invalid @enderror" name="congregation" value="{{ old('congregation') }}"  autocomplete="congregation">

                                    @error('congregation')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </section>
                        <!--End of part a-->

                        <!--Beggining of part b-->
                            {{--<section class="row">
                                <div class="form-group col-md-4">
                                    <label for="sub_parish"  class="col-form-label">{{ __('registration.sub_parish') }}</label>

                                    <div>
                                        <input id="sub_parish" type="text" placeholder="{{ __('registration.sub_parish.placeholder')}}" class="form-control @error('sub_parish') is-invalid @enderror" name="sub_parish" value="{{ old('sub_parish') }}"  autocomplete="sub_parish" autofocus>

                                        @error('sub_parish')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="congregation" class="col-form-label ">{{ __('registration.congregation') }}</label>

                                    <div>
                                        <input id="congregation" placeholder="{{__('registration.congregation.placeholder')}}" type="text" class="form-control @error('congregation') is-invalid @enderror" name="congregation" value="{{ old('congregation') }}"  autocomplete="congregation">

                                        @error('congregation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="house_number" class="col-form-label">{{ __('registration.house_number') }}</label>

                                    <div>
                                        <input id="house_number" type="date" class="form-control @error('house_number') is-invalid @enderror" name="house_number" value="{{ old('house_number') }}"  autocomplete="house_number">

                                        @error('house_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </section>--}}
                        <!--End of part b-->
                        <!--Beggining of part c-->
                            <h4>{{__('registration.details_of_heir')}}</h4>
                            <section class="row">
                                <div class="form-group col-md-6">
                                    <label for="heir_full_name"  class="col-form-label">{{ __('registration.fullname') }}</label>

                                    <div>
                                        <input v-model="name" id="heir_full_name" placeholder="{{ __('registration.fullname') }}" type="text" class="form-control @error('heir_full_name') is-invalid @enderror" name="heir_full_name" value="{{ old('heir_full_name') }}"  autocomplete="heir_full_name" autofocus>

                                        @error('heir_full_name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="relation" class="col-form-label ">{{ __('registration.relation') }}</label>

                                    <div>
                                        <select name="relation" id="relation" class="form-control" @change="RelationOther()" v-model="relation">
                                            <option value="">{{__('select')}}</option>
                                            <option value="{{__('wife')}}">{{ucfirst(__('wife'))}}</option>
                                            <option value="{{__('husband')}}">{{ucfirst(__('husband'))}}</option>
                                            <option value="{{__('child')}}">{{ucfirst(__('child'))}}</option>
                                            <option value="{{__('other')}}">{{ucfirst(__('other'))}}</option>
                                        </select>
                                        @error('relation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <input name="relation" class="form-control" type="text" v-if="relationOther" placeholder="Specify Relationship" required>
                                </div>

                            </section>
                            <section class="row">
                                <div class="form-group col-md-4">
                                    <label for="heir_address"  class="col-form-label">{{ __('registration.address') }}</label>

                                    <div>
                                        <input id="heir_address" type="text" placeholder="{{ __('registration.address.placeholder')}}" class="form-control @error('heir_address') is-invalid @enderror" name="heir_address" value="{{ old('heir_address') }}"  autocomplete="heir_address" autofocus>

                                        @error('heir_address')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="heir_house_number" class="col-form-label">{{ __('registration.house_number') }}</label>

                                    <div>
                                        <input id="heir_house_number" type="text" class="form-control @error('heir_house_number') is-invalid @enderror" name="heir_house_number" value="{{ old('heir_house_number') }}"  autocomplete="heir_house_number">

                                        @error('heir_house_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="heir_phone" class="col-form-label ">{{ __('registration.phone_number') }}</label>

                                    <div>
                                        <input id="heir_phone" placeholder="{{__('registration.phone_number.placeholder')}}" type="number" class="form-control @error('heir_phone') is-invalid @enderror" name="heir_phone" value="{{ old('heir_phone') }}"  autocomplete="heir_phone">

                                        @error('heir_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </section>
                        <!--End of part c-->
                        <!--Beggining of part d-->
                            <h4>{{__('registration.referees')}}</h4>
                            <!-- Referee 2 -->
                            <div class="form-group col-md-6">
                                <label for="ref_1_id"  class="col-form-label">{{ __('registration.member_id_1') }}</label>

                                <div>

                                    <input v-if="message1.length == 0" id="ref_1_id" v-model="referee1" @keyup="CheckReferee(1)" placeholder="{{__('registration.member_id.placeholder_1')}}" type="text" class="form-control @error('ref_1_id') is-invalid @enderror" name="ref_1_id" value="{{ old('ref_1_id') }}"  autocomplete="ref_1_id" autofocus>
                                    <input v-else id="ref_1_id" v-model="referee1" @keyup="CheckReferee(1)" placeholder="{{__('registration.member_id.placeholder_1')}}" type="text" class="border-danger form-control @error('ref_1_id') is-invalid @enderror" name="ref_1_id" value="{{ old('ref_1_id') }}"  autocomplete="ref_1_id" autofocus>
                                    <small class="text-danger" v-text="message1"></small>
                                    @error('ref_1_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Referee 2 -->
                            <div class="form-group col-md-6">
                                <label for="ref_2_id"  class="col-form-label">{{ __('registration.member_id_2') }}</label>

                                <div>
                                    <input id="ref_2_id" v-if="message2.length == 0" v-model="referee2" @keyup="CheckReferee(2)" type="text" placeholder="{{__('registration.member_id.placeholder_2')}}" class="form-control @error('ref_2_id') is-invalid @enderror" name="ref_2_id" value="{{ old('ref_2_id') }}"  autocomplete="ref_2_id" autofocus>
                                    <input id="ref_2_id"  v-else @keyup="CheckReferee(2)" v-model="referee2" type="text" placeholder="{{__('registration.member_id.placeholder_2')}}" class="border-danger form-control @error('ref_2_id') is-invalid @enderror" name="ref_2_id" value="{{ old('ref_2_id') }}"  autocomplete="ref_2_id" autofocus>
                                    <small class="text-danger" v-text="message2"></small>
                                    @error('ref_2_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        <!--End of part d-->
                            <input type="checkbox" name="accept_terms" required> {{__('registration.i_accept')}} <a
                                href="##">{{__('registration.terms_and_condition')}}</a>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" v-if="(message1.length == 0 && message2.length == 0)">
                                    {{ __('Register') }}
                                </button>
                                <button type="submit" class="btn btn-primary" disabled v-else>
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var vm = new Vue({
        el: '#vue',
        data: {
           relationOther: false,
            relation: '',
            referee1: '',
            message1: '',
            referee2: '',
            message2: '',
        },
        methods: {
            RelationOther: function(){
                if(this.relation == 'other'){
                    this.relationOther = true;
                }else{
                    this.relationOther = false;
                }
            },
            CheckReferee: function(ref){
                if(ref == 1){
                    axios.post('{{route('referee.check')}}', {key: this.referee1}).then(function(response){
                        //alert(response.data.message)
                        if(response.data.status==0){
                            vm.message1 = response.data.message;
                        }else if(response.data.status==1){
                            if(vm.referee1 == vm.referee2 && vm.referee1.length>0){
                                vm.message1 = "Please provide two different referees"
                            }else{
                                vm.message1 = response.data.message
                                if(vm.referee2.length>0){
                                    axios.post('{{route('referee.check')}}', {key: this.referee2}).then(function(response){
                                        if(response.data.status==0){
                                            vm.message2 = response.data.message;
                                        }else if(response.data.status==1){
                                            if(vm.referee1 == vm.referee2 && vm.referee2.length>0){
                                                vm.message2 = "Please provide two different referees"
                                            }else{
                                                vm.message2 = response.data.message
                                            }
                                        }
                                    })
                                }
                            }
                        }
                    })
                }else if(ref == 2){
                    axios.post('{{route('referee.check')}}', {key: this.referee2}).then(function(response){
                        //alert(response.data.message)
                        if(response.data.status==0){
                            vm.message2 = response.data.message;
                        }else if(response.data.status==1){
                            if(vm.referee1 == vm.referee2 && vm.referee2.length>0){
                                vm.message2 = "Please provide two different referees"
                            }else {
                                vm.message2 = response.data.message
                                if(vm.referee1.length>0){
                                    axios.post('{{route('referee.check')}}', {key: this.referee1}).then(function(response){
                                        //alert(response.data.message)
                                        if(response.data.status==0){
                                            vm.message1 = response.data.message;
                                        }else if(response.data.status==1){
                                            if(vm.referee1 == vm.referee2 && vm.referee1.length>0){
                                                vm.message1 = "Please provide two different referees"
                                            }else{
                                                vm.message1 = response.data.message
                                            }
                                        }
                                    })
                                }
                            }
                        }
                    })
                }

            }
        }
    })
    //vm.RelationOther();
</script>
@endsection
