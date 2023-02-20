<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required|regex:/^[a-zA-Z ]+$/',
            'lastname' => 'required|regex:/^[a-zA-Z ]+$/',
            'mobile' => 'required|unique:users,mobile,' . $this->user->id,
            'password' => 'required|string|min:6',
            'email' => 'required|email|unique:users,email,' . $this->user->id
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Please Enter First Name',
            'lastname.required' => 'Please Enter Last Name',
            'mobile.required' => 'Please Enter Mobile Number',
            'password.required' => 'Please Enter Password',
            'password.min' => 'Please Enter Minimum 6 letters',
            'email.required' => 'Email is required',
            'email.email' => 'Email format is invalid'
        ];
    }
}
