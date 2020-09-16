@extends('layouts.app')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>--}}
@section('content')
    <div class="container" id="vue">
        {{--<span v-text="name"></span>--}}

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" style="height: 400px; overflow-y: scroll">
                    <table class="table">
                        <div class="spinner-border mt-2" v-if="status=='loading'" style = "position: fixed; right: 50px" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <THEAD>
                        <TR>
                            <TD><strong>Customer Key</strong></TD>
                            <TD><strong>Customer Name</strong></TD>
                            <TD><strong>Loan Type</strong></TD>
                            <TD><strong>Loan ID</strong></TD>
                            <TD><strong>Pay Date</strong></TD>
                            <TD><strong>Amount</strong></TD>
                            <TD>
                                {{--<strong>Units</strong>--}}
                                <span>
                                    <button v-if="logs.length>0 && status != 'loading'" class="float-right btn btn-success" @click="submitLogs()" >Save Logs</button>
                                    <button v-if="status == 'loading'" class="float-right btn btn-success" style="visibility: hidden" >Save Logs</button>
                                </span>
                            </TD>
                        </TR>
                        </THEAD>
                        <tbody >
                            <tr v-for="log,key in logs">
                                <td v-text="log.key"></td>
                                <td v-text="log.name"></td>
                                <td v-text="log.loan_type"></td>
                                <td v-text="log.loan_id"></td>
                                <td v-text="log.date"></td>
                                <td v-text="log.amount"></td>
                                <td><span class="text-danger" @click="removeFromLog(key)">remove</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="logs.length==0" class="text-center my-5 py-5">
                        <p class="text-primary"><strong>Please add logs via the form at the bottom</strong></p>
                    </div>
                    <span id = "last"></span>
                    {{--<div class="card-header"><strong>Viewing Activity: </strong></div>--}}

                </div>
                <div class="card mt-1">
                <table class="table">
                    <THEAD>
                    <TR>
                        <TD>
                            <select v-model="log.key" id = 'customer' @change="customerName(log.key)" class="form-control">
                                <option value="">Customer Name</option>
                                <option v-for="customer in customers" v-bind:value="customer.key" v-text="customer.name"></option>
                            </select>
                        </TD>
                        {{--<TD>
                            <select v-model="log.act_name" class="form-control">
                                <option>Activity</option>
                                <option v-for="activity in activities" v-bind:value="activity.name" v-text="activity.name"></option>
                            </select>
                        </TD>--}}

                        <TD>
                            <select v-model="log.loan_id" id = 'loan' v-on:keyup.enter="preLogging()" class="form-control" @change="loanInstallmentDetails()">
                                <option value="">Loan Type</option>
                                <option v-if="customerLoanRow.type" v-bind:value = "customerLoanRow.id">@{{ customerLoanRow.type }}</option>
                            </select>
                        </TD>
                        <TD><input type="date" v-model="log.date" v-on:keyup.enter="preLogging()" class="form-control" placeholder="date"></TD>
                        {{--<TD><input type="time" v-model="log.time" v-on:keyup.enter="preLogging()" class="form-control"></TD>--}}
                        <TD><input type="number" v-model="log.amount" v-on:keyup.enter="preLogging()" class="form-control" placeholder = "amount paid"></TD>
                        <td><button @click="addToLog()" class = "btn btn-primary">Insert</button></td>
                    </TR>
                    </THEAD>
                </table>
                </div>
            </div>

            {{--<div class="col-md-6">
                <div class="card">
                    <div class="card-header"><strong>Activity Log:</div>
                    <div class="card-body">

                    </div>
                </div>

            </div>--}}
        </div>
    </div>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var token = document.getElementsByTagName('meta')[1].content;

        function addToLog(){

        }
        function searchDiv(){
            var word = document.getElementById('input-search').value;
            var rex = new RegExp(word, 'i');
            $('.searchable-container .items').hide();
            $('.searchable-container .items').filter(function() {
                return rex.test($(this).text());
            }).show();
        }
    </script>
    <script>
        var actNames ;
        const app = new Vue({
            el: "#vue",
            methods: {
                customerName: function(name){
                    /*name = name.replace(/-/g, '_');
                    this.log['act_name'] = this.acts[name];*/
                    if(this.log.key!=''){
                        this.log.name = this.customersObject[this.log.key];
                        this.log.key = this.log.key;
                        this.customerLoan(this.log.key);
                    }else{
                        this.log.name = '';
                        this.log.key = '';
                        this.log.loan_id = '';
                        this.log.amount = '';
                        this.log.date = '';
                    }
                },
                customerLoan: function(customer){
                    //alert(customer);
                    const vm = this;
                      axios.post("{{Route('customer.owing.loan')}}", {customer: customer}).then(function(response){
                          //vm.employees = response.data;
                          ///alert(response.data)
                          vm.customerLoanRow = response.data;
                          vm.log.loan_type = vm.customerLoanRow.type;
                          vm.log.loan_ID = vm.customerLoanRow.id;

                          //alert(vm.customerLoanRow.type);
                      }).catch(function(error){
                          alert(error)
                      })
                },
                loanInstallmentDetails: function(){
                   //alert(this.log.loan_id);
                   axios.post('{{route("installment.details")}}', {loan_id: this.log.loan_id}).then(function (response) {
                       app.log.amount = response.data.amount;
                       app.log.date = response.data.pay_by;
                   })
                },
                removeFromLog: function(key){
                    this.logs.splice(key,1);
                },
                preLogging: function(event){
                   this.addToLog();
                  //alert(event.EVENT_KEY());
                },
                addToLog: function(){
                    if(this.log.name == "Customer Name" || this.log.date == "" || this.log.amount == ""  || this.log.loan_type == ""){
                        alert('Please fill all the boxes');
                    }else{
                        this.logs.push(this.log);
                        this.log = {name: '', key: '', loan_type: '', loan_id: '', date: '', amount: ''}
                    }
                },
                getCustomers: function(){
                    var vm = this;
                    var data =  new FormData();
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
                            vm.customers = response.return;
                            vm.customersObject = response.object;

                        }

                    }
                    xhttp.open("POST", "{{Route('loan.owing.customers')}}", true);
                    xhttp.setRequestHeader('X-CSRF-TOKEN', token);
                    xhttp.send(data);
                    /*axios.post("{{Route('loan.owing.customers')}}").then(
                        function(response){
                            vm.acts=response.data.activities;
                            vm.employees=response.data.employees;
                            vm.employee_codes=response.data.employee_codes;
                            vm.activities=response.data.acts;

                        }).catch(
                            function(error){alert(error)
                            });*/
                },
                updateEmployee: function(value){
                    var rm = this;
                    value = value.replace(/ /g, '_');
                    rm.log['employee_id'] = rm.employee_codes[value];
                },
                submitLogs: function(){
                    const vm =  this;
                    vm.status = 'loading';
                    var data =  new FormData();
                    data.append('data', JSON.stringify(this.logs))
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
                            var response = this.responseText;
                            if(response == 1){
                                //alert("data successfuly saved");
                                vm.logs = [];
                                vm.status = '';
                                swal("Success!", "Data successfuly Saved", "success")

                            }

                        }

                    }
                    xhttp.open("POST", "{{Route('loan.repayment.save')}}", true);
                    xhttp.setRequestHeader('X-CSRF-TOKEN', token);
                    xhttp.send(data);
                    /*axios.post("{{Route('loan.repayment.save')}}", {data: this.logs}).then(function(response){
                        //alert(response.data)
                        //vm.name = response.data;
                        //alert(vm.name);
                        if(response.data == 1){
                            //alert("data successfuly saved");
                            vm.logs = [];
                            swal("Success!", "Data successfuly Saved", "success")
                            vm.status = '';
                        }
                    }).catch(function(error){
                        alert(error)
                        vm.status = '';
                    })*/

                },

            },
            data: {
                name: "peter",
                status: '',
                log: {name: "Customer Name", key: '', loan_type: 'Loan Type', loan_id: '', installment: 'Installment Number', date: '', time: '', amount: ''},
                logs: [
                    /*{activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-MAN-876', act_name: 'Manufacturing',employee: "Peter R Mgembe", date: "21/10/2019", time: "08:30:30", units: 5},
                    {activity: 'ACT-PAC-815', act_name: 'Packaging', employee: "Deodatus R Mgembe", date: "26/10/2019", time: "09:00:30", units: 15}*/
                ],
                loans: [
                    {name: "Education Loan", code:  "ACT-MAN-876"},
                    {name: "Development Loan", code:  "ACT-PAC-815"},
                ],
                acts: [],
                employee_codes: [],
                 customers: [

                ],
                customersObject:{

                },
                customerLoanRow: {

                }
                //act_names:
            }
        });

        function activities(){
            var token = document.getElementsByTagName('meta')[1].content;
            var data = new FormData();
            if (window.XMLHttpRequest) {
                // code for modern browsers
                xhttp = new XMLHttpRequest();
            } else {
                // code for old IE browsers
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                   //actNames = this.responseText;
                    //console.log(this.responseText);

                    actNames = this.responseText;
                    //console.log(actNames);
                  //  alert(this.responseText);
                    //alert(app.name);
                    app.name += "George";
                   //return actNames;
                    updateName("George");
                }
            }
            xhttp.open("POST", "{{--{{Route('activities.names')}}--}}", true);
            xhttp.setRequestHeader('X-CSRF-TOKEN', token);
            xhttp.send(data);
        }
    //alert(app.logs.length)

        function updateName(name){
            app.name = name;
        }
        app.getCustomers();
        //updateName("james")
        //alert(app.name);
    </script>
@endsection
