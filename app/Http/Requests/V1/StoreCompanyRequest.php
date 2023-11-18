<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required'],
            'email' =>['required'],
            'address' =>['required'],
            'district'=>['required'],
            'city'=>['required'],
            'phone'=>['required','numeric'],
            'logo'=>['required'],
            'detail'=>['required'],
            'url_page'=>['required'],
        //     '*.customerId' => ['required','integer'],
        //     '*.amount' => ['required','numeric'],
        //     '*.status' => ['required',Rule::in(['P','B','V','p','b','v'])],
        //     '*.billedDate' => ['required','date_format:Y-m-d H-i-s'],
        //     '*.paidDate' => ['date_format:Y-m-d H-m-s','nullable']
        ];
    }
    protected function prepareForValidation()
    {
        // $data =[];
        // foreach ($this->toArray() as $obj){
        //     $obj['customer_id'] = $obj['customerId'] ?? null;
        //     $obj['billed_date'] = $obj['billedDate'] ?? null;
        //     $obj['paid_date'] = $obj['paidDate'] ?? null;

        //     $data[] =$obj; 
        // }
        // $this->merge($data);
    }
}
