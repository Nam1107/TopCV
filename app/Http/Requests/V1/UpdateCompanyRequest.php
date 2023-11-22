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
        return false;
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
            "name"=>['somethings','required'],
                "email" =>['somethings','required','email'],
                "address" =>['somethings','required'],
                "district"=>['somethings','required'],
                "city"=>['somethings','required'],
                "phone"=>['somethings','required','numeric'],
                "logo"=>['somethings','required'],
                "detail"=>['somethings','required'],
                "url_page"=>['somethings','required'],
                "follow_count"=> ['somethings','required'],
        ];
    }
}
