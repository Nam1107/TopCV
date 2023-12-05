<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sex_req = ['Nam','Nữ','Không yêu cầu'];
        $method = $this->method();
        if($method == 'PUT'){
            
            return [
                'title' => 'required',
                'salary' => 'required',
                'sex_required' => ['required',Rule::in($sex_req)],
                'quantity' => 'required',
                'level_required' => 'required',
                'field_of_job' => 'required',
                'company_id'=> 'required',
                'created_by'=> 'required',
                'expire_at'=> 'required',
            ];
        }else{
            return [
                'title' => 'sometimes|required',
                'salary' => 'sometimes|required',
                'sex_required' => ['sometimes','required',Rule::in($sex_req)],
                'quantity' => 'sometimes|required',
                'level_required' => 'sometimes|required',
                'field_of_job' => 'sometimes|required',
                'company_id'=> 'sometimes|required',
                'created_by'=> 'sometimes|required',
                'expire_at'=> 'sometimes|required',
            ];
        }
    }
}
