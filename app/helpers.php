<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;



if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param DateTimeZone|string|null $tz
     * @return Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}

if (! function_exists('redisKey')) {
    /**
     * 统计redis键名管理
     *
     * @param $prefix
     * @param mixed ...$args
     * @param string $redisKeyClass
     * @return string
     * @throws ReflectionException
     */
    function redisKey($prefix, array $args, $redisKeyClass = '\App\Common\RedisKey')
    {
        if (!(new ReflectionClass($redisKeyClass))->hasConstant($prefix)) {
            throw new Exception('请确认redis键名正确', 10000);
        }

        if (!class_exists($redisKeyClass)) {
            throw new Exception('定义key的类不存在', 10000);
        }

        $ext = '';
        if (!empty($args)) {
            $result = [];
            array_walk_recursive($args, function ($value, $key) use (&$result) {
                if (!is_numeric($key)) {
                    $value = $key . '_' . $value;
                }
                array_push($result, $value);
            });
            $ext .= implode(':', $result);
        }
        return rtrim(rtrim($prefix, ':') . ':' . $ext, ':');
    }
}

if (!function_exists('success')) {
    function success(array $data = [], $httpCode = 200)
    {
        return response()->json([
            'code'       => 200,
            'message'    => 'success',
            'request_id' => $_SERVER['x_request_id'] ?? REQUEST_ID,
            'data'       => !empty($data) ? $data : (object)$data
        ], $httpCode);
    }
}

if (!function_exists('error')) {
    function error($code, $args = [], $data = [], $httpCode = 200)
    {
        [$code, $message] = message($code, $args);

        if (!empty($args)) {
            $message = vsprintf($message, $args);
        }
        // TODO
        throw new \App\Exceptions\Error($code, $message, $data, $httpCode);
    }
}

if (!function_exists('message')) {
    function message($code, $args)
    {
        if (is_numeric($code)) {
            if (empty(config('code.' . $code)) && empty($args)) {
                $message = '未知的错误';
            } else if (!empty($args)) {
                $message = '%s';
            } else {
                $message = config('code.' . $code);
            }
        } else {
            $config  = config('notice.' . $code);
            $arr     = explode('|', $config);
            $message = $arr[1];
            $code    = $arr[0];
        }

        if (!empty($args)) {
            $message = vsprintf($message, $args);
        }

        return [$code, $message];
    }
}
