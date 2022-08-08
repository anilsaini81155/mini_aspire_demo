<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/miniaspireapp/user')->middleware(['AuthenticateClientRequest'])->group(function () {
    
    Route::post('/CreateLoanRequest', 'LoanController@createLoanRequest');
    Route::get('/GetLoanRepaymentSchedule', 'LoanController@getLoanRepaymentSchedule');
    Route::patch('/PayEmi', 'LoanController@payEmi');
    Route::get('/GetLoanDetails', 'LoanController@getLoanDetails');
    
});


Route::prefix('/miniaspireapp/admin')->middleware(['AuthenticateClientRequest'])->group(function () {
    
    Route::post('/CreateLoanRequest', 'AdminController@createLoanRequest');
    Route::post('/ApproveLoan', 'AdminController@approveLoan');
    Route::get('/GetLoanRepaymentSchedule', 'AdminController@getLoanRepaymentSchedule');
    Route::patch('/PayEmi', 'AdminController@payEmi');
    Route::get('/GetLoanDetails', 'AdminController@getLoanDetails');
    
});


Route::prefix('/miniaspireapp/open-call')->group(function () {
    Route::get('/login', 'AdminController@login');
    Route::post('/signup', 'AdminController@signup');
});
