<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $method = request()->method();
        if($method == 'PUT'){
            return [

                'name' => ['required'],
                'email'=> ['required','email'],
                'sex'=> ['required',Rule::in(['Nam','Nữ'])],
                'phone'=> ['required','numeric'],
                'avatar' =>['required'],
                'address' =>['required'],
                'district'=> ['required'],
                'province' => ['required'],
            ];
        }
        return [
            //
            'name' => ['sometimes','required'],
            'email'=> ['sometimes','required','email'],
            'sex'=> ['sometimes','required',Rule::in(['Nam','Nữ'])],
            'phone'=> ['sometimes','required','numeric'],
            'avatar' =>['sometimes','required'],
            'address' =>['sometimes','required'],
            'district'=> ['sometimes','required'],
            'province' => ['sometimes','required'],
        ];
    }
}
