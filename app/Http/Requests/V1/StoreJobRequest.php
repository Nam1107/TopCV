<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
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
        // 'title', 
        // 'salary', 
        // 'sex_required', 
        // 'desc', 
        // 'exp_required',
        // 'quantity' ,
        // 'level_required' ,
        // 'field_of_job' ,
        // 'company_id' ,
        // 'created_by' ,
        // 'expire_at' ,
        // 'status' ,
        $sex_req = ['Nam','Nữ','Không yêu cầu'];
        return [
            'title' => 'required',
            'salary' => 'required',
            'sex_required' => ['required',Rule::in($sex_req)],
            'quantity' => 'required',
            'level_required' => 'required',
            'field_of_job' => 'required',
            'company_id'=> 'required',
            // 'created_by'=> 'required',
            'expire_at'=> ['required','date_format:Y-m-d H:i:s'],
            // 'status'=> 'required',
        ];
    }
}
