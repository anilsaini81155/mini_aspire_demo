<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Http\Repository\LoanRepository;
use App\Services\EmiService;
use App\Services\LoanService;

class LoanController
{

    protected $loanService;
    protected $emiService;

    public function __construct(LoanService $loanService , EmiService $emiService)
    {
        $this->loanService = $loanService;
        $this->emiService = $emiService;
    }

    public function createLoanRequest(Requests\CreateLoanRequest $a){

        $result = $this->loanService->processLoansRequest($a->all(),Config('commonconfig.user_type.User'));

        if ($result == false) {
            return response()->json([
                "message" => "User Loan Not Created"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "User Loan Created Successfully.Please find loan details." , "data" => $result
        ], 200);
            
    }

    public function getLoanRepaymentSchedule(Requests\GetLoanRepaymentScheduleRequest $a){

        $result = $this->emiService->getDetailsOfLoanRepaymentSchedule($a);

        if($result == false){
            return response()->json([
                "message" => "User Loan EMI Details Not Found"
            ], 404);

        }

        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "User Loan EMI Details Fetched. Successfully." , "data" => $result
        ], 200);
    }
    
    public function payEmi(Requests\PayEmiRequest $a){

       $result = $this->emiService->processLoanEmi($a);

        if($result['status'] == false){
            return response()->json([
                "message" => $result['message']
            ], 202);
        }

      return response()->json([
        "message" => $result['message']], 200);
    }

    public function getLoanDetails(Requests\GetLoanDetailsRequest $a){

        $result = $this->loanService->getLoanDetails($a);

        if($result == false){
            return response()->json([
                "message" => "User Loan Details Not Found"
            ], 404);

        }

        if($a->user_id != $result->user_id){
            return response()->json([
                "message" => "Other User Loan Cannot Be fetched."
            ], 403);
        }

        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "User Loan Details Fetched. Successfully." , "data" => $result
        ], 200);
    }   
   
}
