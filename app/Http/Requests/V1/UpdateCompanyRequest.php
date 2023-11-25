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
                "email" =>['required','email'],
                "address" =>['required'],
                "district"=>['required'],
                "city"=>['required'],
                "phone"=>['required','numeric'],
                "logo"=>['required'],
                "detail"=>['required'],
                "url_page"=>['required'],
            ];
        }
        return [
            //
            "name"=>['sometimes','required'],
                "email" =>['sometimes','required','email'],
                "address" =>['sometimes','required'],
                "district"=>['sometimes','required'],
                "city"=>['sometimes','required'],
                "phone"=>['sometimes','required','numeric'],
                "logo"=>['sometimes','required'],
                "detail"=>['sometimes','required'],
                "url_page"=>['sometimes','required'],
                "follow_count"=> ['sometimes','required'],
        ];
    }
}
