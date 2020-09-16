<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
 * ********************************************************************************************************************
 *                              REGISTRATION ROUTES
 * ********************************************************************************************************************
 */
Route::get('/member/registration', 'MembershipController@RegistrationForm')->name('member.register');
Route::post('/member/registration/save', 'MembershipController@RegistrationFormSave')->name('member.register.save');
Route::get('/member/registration/save/notification', 'MembershipController@Notification')->name('notification');
Route::get('/registrations', 'MembershipController@Registrations')->name('registrations');
Route::post('/registration/information', 'MembershipController@RegistrationInformation')->name('registration.info');
Route::post('/registration/find', 'MembershipController@RegistrationSearch')->name('registration.find');
Route::post('/registration/approve', 'MembershipController@RegistrationApprove')->name('registration.approve');
Route::post('/referee/check', 'MembershipController@RefereeCheck')->name('referee.check');

/*
 * ********************************************************************************************************************
 *                              LOAN ROUTES
 * ********************************************************************************************************************
 */
Route::get('/loan/request', 'LoanController@LoanRequest')->name('loan.request');
Route::get('/loan/requests', 'LoanController@LoanRequests')->name('loan.requests');
Route::post('/loan/request/submit', 'LoanController@LoanRequestSubmit')->name('loan.request.submit');
Route::post('/loan/info', 'LoanController@LoanInfo')->name('loan.info');
Route::post('/loan/types', 'LoanController@LoanTypes')->name('loan.types');
Route::post('/loan/approve', 'LoanController@Approve')->name('loan.approve');
Route::post('/loan/confirm/disbursement', 'LoanController@ConfirmDisbursement')->name('loan.confirm.disbursement');
Route::get('/loan/repayment/log', 'LoanController@RepaymentLogger')->name('loan.repayment.logger');
Route::post('/loan/owing-customers', 'LoanController@OwingCustomers')->name('loan.owing.customers');
Route::post('/customer-loan', 'LoanController@CustomerOwingLoan')->name('customer.owing.loan');
Route::post('/loan/repayment/log/save', 'LoanController@RepaymentLogSave')->name('loan.repayment.save');
Route::post('/loan/max-amount', 'LoanController@LoanMaxAmount')->name('loan.max-amount');
Route::post('/loan/installment/detail', 'LoanController@InstallmentDetails')->name('installment.details');

/*
 * ********************************************************************************************************************
 *                              CASHFLOW ROUTES
 * ********************************************************************************************************************
 */
Route::get('/withdrawal/request', 'AccountController@RequestWithdrawal')->name('withdrawal.request');
Route::post('/account/types', 'AccountController@AccountTypes')->name('account.types');
Route::post('/withdraw/max-amount', 'AccountController@WithdrawMaxAmount')->name('account.max-amount');
Route::post('/withdraw/request/submit', 'AccountController@WithdrawRequestSubmit')->name('withdraw.request.submit');
