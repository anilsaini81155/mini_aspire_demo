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

class AdminController
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
                "message" => "Record not found"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "Data Fetched Successfully" , "data" => $result
        ], 200);
            
    }

    public function getLoanRepaymentSchedule(Requests\GetLoanRepaymentScheduleRequest $a){

        $this->emiService->getDetailsOfLoanRepaymentSchedule($a);
    }
    
    public function payEmi(Requests\PayEmiRequest $a){

        $this->emiService->processLoanEmi($a);
    }

    public function getLoanDetails(Requests\GetLoanDetailsRequest $a){

        $this->loanService->getLoanDetails($a);
    }   
   
}
