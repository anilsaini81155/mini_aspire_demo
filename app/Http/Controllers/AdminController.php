<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Http\Repository;

class AdminController
{

    protected $loanService;
    protected $tokenService;
    protected $emiService;


    public function __construct(Services\LoanService $loanService , Services\TokenService $tokenService , Services\EmiService $emiService ,Repository\UserRepository $userRepo)
    {
        $this->loanService = $loanService;
        $this->tokenService = $tokenService;
        $this->emiService =$emiService;
        $this->userRepo = $userRepo;
    }

    public function login(Request $a){
        $a->validate([
            "email" => ["required", "email"],
            "password" => ["required", "string", "max:16"]
        ]);

        $user = User::where([["email", $a->input("email")]])->first();

        if ($user instanceof User) {
            
            if (Crypt::decrypt($user->password) == $a->password) {
                
                $result = $this->tokenService->processUserForToken($user);
                
                if($result == false){
                    return response()->json([
                        "message" => "Unable to generate the token"
                    ], 403);
                }

                return response()->json([
                    "message" => "Token generated successfully" , "token" => $result
                ], 200);


            } else {

                return response()->json([
                    "message" => "Incorrect Password"
                ], 403);
            }
        } else {
            return response()->json([
                "message" => "Incorrect Details Provided"
            ], 403);
        }

    }

    public function signup(Request $a){

        $a->validate([
            "name" => ["required"],
            "email" => ["required", "email"],
            "mobile_no" => ["required", "numeric", "digits:10", "regex:/^[7-9][0-9]{9}$/"],
            "password" => ["required", "string", "max:16"]
        ]);
        
        $insertData =  [
            'name' => $a->name,
            'mobile_no' => $a->mobile_no,
            'email' => $a->email,
            'password' =>  Crypt::encrypt($a->password),
            'max_no_loan' => Config('commonconfig.max_no_of_loan'),
            'type' => Config('commonconfig.user_type.User'),
            'created_at' => now()
        ];

        
        $result =  $this->userRepo->create($insertData);  

        if ($result == false) {
            return response()->json([
                "message" => "User not created"
            ], 404);
        }
        
        return response()->json([
            "message" => "User SignedUp Successfully" 
        ], 201);

    }

    public function approveLoan(Request $a){

        $a->validate([
            "loan_id" => ["required", "numeric"]
        ]);

        if($a->user_type != Config('commonconfig.user_type.Admin')){
            return response()->json([
                "message" => "UnAuthorised Access Found." 
            ], 403);
        }

        $result = $this->loanService->approveUserLoansRequest($a->all());

        if ($result == false) {
            return response()->json([
                "message" => "Loan not approved, please check the logs."
            ], 404);
        }

        return response()->json([
            "message" => "Loan Approved Successfully." 
        ], 200);

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
            "message" => "User Loan Created Successfully.Please find loan id." , "data" => $result
        ], 200);
    }

    public function getLoanRepaymentSchedule(Requests\GetLoanRepaymentScheduleRequest $a){

        $result =  $this->emiService->getDetailsOfLoanRepaymentSchedule($a);

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

        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "User Loan Details Fetched. Successfully." , "data" => $result
        ], 200);


    }
   
}
