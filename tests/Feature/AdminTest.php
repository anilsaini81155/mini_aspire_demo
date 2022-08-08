<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_login()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/miniaspireapp/open-call/login', [
            'email' => 'abc@xyz.com',
            'password' => 'apq@ad45'
        ]);
        $response->assertStatus(403);
    }

    public function test_signup()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/miniaspireapp/open-call/signup', [
            'email' => 'abc@xyz.com',
            'name' => 'apqad45',
            'mobile_no' => 7867612345,
            'password' => 'apq@ad45'
        ]);
        $response->assertStatus(201);
    }

    public function test_create_loan_request(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->post('/api/miniaspireapp/admin/CreateLoanRequest', [
            'loan_amount' => 15000,
            'loan_tenure' => 3,
            'user_id' => 1
        ]);
        $response->assertStatus(200);   
    }


    public function test_approve_loan_request(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->post('/api/miniaspireapp/admin/ApproveLoan', [
            'loan_id' => 10
        ]);
        $response->assertStatus(404);   
    }


    public function test_repayment_schedule()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api/miniaspireapp/admin/GetLoanRepaymentSchedule', [
            'loan_id' => 10
        ]);
        $response->assertStatus(404);
    }

    
    public function test_pay_loan_emi_request(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->post('/api/miniaspireapp/admin/PayEmi', [
            'loan_id' => 10,
            'emi_id' => 11,
            'emi_amount' => 500,
            'user_id' => 1
        ]);
        $response->assertStatus(202);   
    }

    public function test_loan_details()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Bearer Token' => '12dasdsajnad34dasdsajna',
        ])->get('/api/miniaspireapp/admin/GetLoanDetails', [
            'loan_id' => 10
        ]);
        $response->assertStatus(404);
    }
}
