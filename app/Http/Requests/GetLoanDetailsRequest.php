<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GetLoanDetailsRequest extends FormRequest{

     /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "loan_id" => "required|numeric"
        ];
    }
    
    
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            
        ];
    }


    public function authorize()
    {
        return true;
    }

}
