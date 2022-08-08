<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;

class EmiService
{
    protected $loanRepo;
    protected $repaymentRepo;

    public function __construct(Repository\LoanRepository $loanRepo , Repository\LoanRepaymentRepository $repaymentRepo)

    {
        $this->loanRepo = $loanRepo;
        $this->repaymentRepo = $repaymentRepo;
    }

  
    public function getDetailsOfLoanRepaymentSchedule($a){

        $emiRecords = $this->repaymentRepo->getSchedule($a->loan_id);

        if ($emiRecords->isEmpty()) {
            return  false;
        }

        return  $emiRecords;

    }
    

    public function processLoanEmi($a){

        DB::beginTransaction();

        try {

            $emiRecord = $this->repaymentRepo->select($a->emi_id);
            
            if ($emiRecord == false) {
                return ['status' => false , 'message' => 'EMI Not found'];
            }
            
            if ($emiRecord->emi_amount !== $a->emi_amount && $emiRecord->emi_amount >= $a->emi_amount) {
                return ['status' => false , 'message' => 'EMI Amount Not Matched || Partial EMI not allowed'];
            }

            if ($emiRecord->status == config('commonconfig.emi_status.Paid')) {
                return ['status' => false , 'message' => 'EMI Already Paid'];
            }

            if($a->emi_amount > $emiRecord->emi_amount ){

                $emiUpdateCount =  $this->repaymentRepo->update(['emi_status' => config('commonconfig.emi_status.Paid'),
                    'amount_received' => $a->emi_amount ,'post_payment_principal_outstanding' => $emiRecord->principal_outstanding - ($a->emi_amount - $emiRecord->emi_amount)
                ], $a->emi_id);
                
            }else{

                $emiUpdateCount =  $this->repaymentRepo->update(['emi_status' => config('commonconfig.emi_status.Paid'),
                   'amount_received' => $a->emi_amount  , 'post_payment_principal_outstanding' => $emiRecord->principal_outstanding ], $a->emi_id);

            }

            
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

            return ['status' => true , 'message' => $msg];

        } catch (\Exception $ex) {
            
            DB::rollback();
            Log::info($ex);
        }

        return ['status' => true , 'message' => $ex->getMessage()];

    }
}
