<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    //  注册成功和失败响应宏
    public function boot()
    {
        Response::macro('success', function ($code = 0, $msg = '请求成功', $data = []) {

            $response_data = [
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            ];

            //  日志记录请求
            if (config('app.debug') == true) {
                app('log')->debug(sprintf('params [%s] response [%s]',
                    json_encode(request()->all(), JSON_UNESCAPED_UNICODE),
                    json_encode($response_data, JSON_UNESCAPED_UNICODE)
                ));
            }

            return Response::json($response_data);
        });

        Response::macro('fail', function ($code = 10000, $msg = '请求失败', $data = []) {

            $response_data = [
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            ];

            //  日志记录请求
            if (config('app.debug') == true) {
                app('log')->debug(sprintf('params [%s] response [%s]',
                    json_encode(request()->all(), JSON_UNESCAPED_UNICODE),
                    json_encode($response_data, JSON_UNESCAPED_UNICODE)
                ));
            }

            return Response::json($response_data);
        });
    }
}
