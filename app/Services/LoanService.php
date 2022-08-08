<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;
use App\Http\Repository\LoanRepository;
use Illuminate\Cache\Repository as CacheRepository;
use Log;

class LoanService
{
    
    protected $userRepo;

    public function __construct(Repository\UserRepository $userRepo , Repository\LoanRepaymentRepository $loanRepayment ,Repository\LoanRepository $loanRepo)
    {
        $this->userRepo = $userRepo;
        $this->loanRepayment = $loanRepayment;
        $this->loanRepo = $loanRepo;
    }

    public function processLoansRequest($a,$user_type){
        
        $loanData = [
            'user_id' => $a['user_id'], 
            'loan_amount' => $a['loan_amount'],
            'loan_tenure' => $a['loan_tenure'],
            'status' =>  Config('commonconfig.loan_status.Pending'),
            'type' => $user_type,
            'created_at' => now()
        ];

        return $this->loanRepo->create($loanData);
    }


    public function approveUserLoansRequest($a){

        DB::beginTransaction();

        try {

        $loanDetails = $this->loanRepo->select($a['loan_id']);                
        
        $currentDate =  strtotime(date('Y-m-d'));

            $startDate = date('Y-m-d', strtotime("+8 days", $currentDate));

            $startDate = strtotime($startDate);
            
            $emiAmount = ($loanDetails->loan_amount / ($loanDetails->loan_tenure));

            $updateLoanData = [
                'loan_status' => Config('commonconfig.loan_status.Approved'),
                'updated_at' => now(),
                'emi_amount' => $emiAmount
            ];    

            $result = $this->loanRepo->update($updateLoanData , $a['loan_id']);    
            $inc = 0;
            $emiSum = $emiAmount;
            for ($i = 0; $i < ($loanDetails->loan_tenure); $i++) {

                $emiScheduleData[] = [
                    'emi_amount' => $emiAmount,
                    'emi_date' => date('Y-m-d', strtotime("+{$inc}   days", $startDate)),
                    'loan_id' => $a['loan_id'],
                    'principal_outstanding' => ($loanDetails->loan_amount - $emiSum)
                ];

                $inc = $inc + 7;
                $emiSum += $emiAmount;
            }

            $this->loanRepayment->insert($emiScheduleData);

            DB::commit();

            return true;

        }catch(\Exception $ex){
              
            DB::rollback();
            Log::info($ex);

            return false;
        }

    }


    public function getLoanDetails($a){
        
        $data = $this->loanRepo->select($a->loan_id);
        
        if($data == false){
            return false;
        }

        return $data;

    }



   
}
