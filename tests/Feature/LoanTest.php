<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class LoanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_create_loan_request(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->post('/api//miniaspireapp/user/CreateLoanRequest', [
            'loan_amount' => 15000,
            'loan_tenure' => 3
        ]);
        $response->assertStatus(200);   
    }

    public function test_user_repayment_schedule()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api//miniaspireapp/user/GetLoanRepaymentSchedule', [
            'loan_id' => 10
        ]);
        $response->assertStatus(404);
    }

    
    public function test_user_pay_loan_emi_request(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->post('/api//miniaspireapp/user/PayEmi', [
            'loan_id' => 10,
            'emi_id' => 11,
            'emi_amount' => 500
        ]);
        $response->assertStatus(202);   
    }

    public function test_user_loan_details()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api//miniaspireapp/user/GetLoanDetails', [
            'loan_id' => 10
        ]);
        $response->assertStatus(404);
    }
}
