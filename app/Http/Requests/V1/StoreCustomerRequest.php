<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreCustomerRequest extends FormRequest
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
        $arr = ['I','B','i','b'];

        return [
            //
            'name' => 'required',
            'type' => ['required',Rule::in($arr)],
            'email' => 'required|unique:customers|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postalCode'=> 'required|unique:customers,postal_code',
        ];

    }
    public function messages(): array
{
    return [
        'required' => ':attribute không được bỏ trống',//Viet tat cua 'name.required' => 'Name không được bỏ trống'
        'in'=>':attribute không hợp lệ',
        'email'=>':attribute không hợp lệ',
        'unique'=>':attribute đã tồn tại'

    ];
}
    protected function prepareForValidation()
    {
        $this->merge([
            'postal_code' => $this->postalCode
        ]);
        
    }
}
