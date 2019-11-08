<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends BaseController
{
    /**
     * 登录
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('account', $request->account)->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->fail(10000, '密码错误');
        }

        set_user_info($user);

        return response()->success(0, '登陆成功');
    }

    /**
     * 登出
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return response()->success(0, '登出成功');
    }

    /**
     * 注册
     * @param RegisterRequest $request
     * @param User $user
     * @return mixed
     */
    public function register(RegisterRequest $request, User $user)
    {
        $captcha_data = Cache::get($request->captcha_key);
        if (!$captcha_data) {
            //  验证码已失效
            return response()->fail(10000, '验证码已失效');
        }

        if (!hash_equals($captcha_data['code'], $request->captcha_code)) {
            //  验证错误就清除缓存
            Cache::forget($request->captcha_key);
            return response()->fail(10000, '验证码错误');
        }

        $user->account = $request->account;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->success(0, '注册成功');
    }

    /**
     * 获取图片验证码
     * @param CaptchaBuilder $captcha_builder
     * @return mixed
     */
    public function getCaptcha(CaptchaBuilder $captcha_builder)
    {
        $key = 'captcha-' . str_random(15);

        $captcha = $captcha_builder->build();

        $expired_at = now()->addMinutes(2);

        Cache::put($key, ['code' => $captcha->getPhrase()], $expired_at);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expired_at->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ];

        return response()->success(0, '获取成功', $result);
    }
}
