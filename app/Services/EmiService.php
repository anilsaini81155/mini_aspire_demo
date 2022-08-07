<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;

class EmiService
{
    protected $loanRepo;
    protected $repaymentRepo;

    public function __construct(Repository\LoanRepository $loanRepo , Repository\RepaymentRepository $repaymentRepo)

    {
        $this->loanRepo = $loanRepo;
        $this->repaymentRepo = $repaymentRepo;
    }

  

    public function getDetailsOfLoanRepaymentSchedule($a){

        $emiRecords = $this->loanRepaymentRepo->getSchedule($a->loan_id);

        if ($emiRecords->isEmpty()) {
            return  false;
        }

        return  $emiRecords->all();


    }
    

    public function processLoanEmi($a){

        DB::beginTransaction();

        try {

            $emiRecord = $this->loanRepaymentRepo->select($a->emi_id);
            
            if ($emiRecord == false) {
                return false;
            }

            if ($emiRecord->emi_amount !== $a->emi_amount) {
                return  false;
            }

            if ($emiRecord->status == config('commonconfig.emi_status.Paid')) {
                return false;
            }

            $emiUpdateCount =  $this->repaymentRepo->update(['emi_status' => config('commonconfig.emi_status.Paid')], $a->emi_id);
            
            if ($emiUpdateCount == 0) {
                throw new \Exception("Updation failed");
            }
            
            if ($emiRecord->principal_outstanding == 0) {
                $msg = "Congratulations All EMIs are paid";
                $count = $this->loanRepo->update(['loan_status' => config('commonconfig.loan_status.Closed')], $emiRecord->loan_id);
                if ($count == 0) {
                    throw new \Exception("Updation failed");
                }
            } else {
                $msg = "Thank you for the EMI.";
            }

            DB::commit();

            return  false;
        } catch (\Exception $ex) {
            
            DB::rollback();
            Log::info($ex);
        }

        return  false;


    }
}
