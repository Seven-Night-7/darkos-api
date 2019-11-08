<?php

namespace App\Http\Requests;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|unique:users,account',
            'password' => 'required|between:6,12',
            'password_confirmation' => 'required|same:password',
            'captcha_key' => 'required',
            'captcha_code' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'account' => '账号',
            'password' => '密码',
            'password_confirmation' => '确认密码',
            'captcha_key' => '验证码标识',
            'captcha_code' => '验证码',
        ];
    }
}
