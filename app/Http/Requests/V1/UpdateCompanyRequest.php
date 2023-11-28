<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
                "name"=>['required'],
                "address" =>['required'],
                "district"=>['required'],
                "province"=>['required'],
                "phone"=>['required','numeric'],
                "logo"=>['required'],
                "detail"=>['required'],
                "url_page"=>['required'],
            ];
        }
        return [
            //
            "name"=>['sometimes','required'],
            "address" =>['sometimes','required'],
            "district"=>['sometimes','required'],
            "province"=>['sometimes','required'],
            "phone"=>['sometimes','required','numeric'],
            "logo"=>['sometimes','required'],
            "detail"=>['sometimes','required'],
            "url_page"=>['sometimes','required'],
        ];
    }
}
