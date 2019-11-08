<?php

namespace App\Http\Requests;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|exists:users,account',
            'password' => 'required|between:6,12',
        ];
    }

    public function attributes()
    {
        return [
            'account' => '账号',
            'password' => '密码',
        ];
    }
}
