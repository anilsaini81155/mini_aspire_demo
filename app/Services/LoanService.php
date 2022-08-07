<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;
use App\Http\Repository\LoanRepository;

class LoanService
{
    
    protected $tokenRepo;
    protected $userRepo;

    public function __construct(Repository\TokenRepository $tokenRepo,Repository\UserRepository $userRepo)
    {
        $this->tokenRepo = $tokenRepo;
        $this->userRepo = $userRepo;
    }

    public function processLoansRequest($a,$user_type){
        
        $loanData = [
            'user_id' => $a['user_id'], //apend the user id from the  middleware
            'loan_amount' => $a['loan_amount'],
            'loan_tenure' => $a['loan_tenure'],
            'status' =>  'Pending', //make config driven 
            'type' => $user_type,
            'created_at' => now()
        ];

        return $this->repo->setRepo(LoanRepository::class)->fetch()
        ->create($loanData);

    }


    public function approveUserLoansRequest($a){

        DB::beginTransaction();

        try {

        $loanDetails = $this->repo->setRepo(LoanRepository::class)->fetch()
                        ->select($a['loan_id']);                
        
        $currentDate =  strtotime(date('Y-m-d'));

            $startDate = date('Y-m-d', strtotime("+8 days", $currentDate));

            $startDate = strtotime($startDate);
            
            $emiAmount = ($loanDetails->loan_amount / ($loanDetails->loan_tenure * 4));

            $updateLoanData = [
                'status' => 'Approved',
                'updated_at' => now(),
                'emi_amount' => $emiAmount
            ];    

            $result = $this->repo->setRepo(LoanRepository::class)->fetch()
                     ->update($a['loan_id'],$updateLoanData);    

            for ($i = 0; $i < ($a->loan_tenure * 4); $i++) {

                $inc = 7;
                
                $emiScheduleData[] = [
                    'emi_amount' => $emiAmount,
                    'emi_date' => date('Y-m-d', strtotime("+{$inc}   days", $startDate)),
                    'loan_id' => $a['loan_id']
                ];

                $inc = $inc + 7;
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

        return  $this->userRepo->getUserSpecifiLoanDetails($a->id);

    }



   
}
