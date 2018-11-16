<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 返回数据格式固定
 * @param $data
 * @param int $code
 * @param string $message
 * @param int $httpCode
 * @param array $header
 * @param array $options
 * @return \think\response\Json
 */
function show($data, $code = 0, $message = 'success', $httpCode = 200, $header = [], $options = [])
{
    $data = [
        'code' => $code,
        'message' => $message,
        'data' => $data
    ];
    return json($data, $httpCode, $header, $options);
}

/**
 * 验证码随机字符串生成
 * @param $length
 * @return string
 */
function getRandStr($length)
{
    $str     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len     = strlen($str) - 1;
    $randstr = '';
    for ($i = 0; $i < $length; $i++) {
        $num     = mt_rand(0, $len);
        $randstr .= $str[$num];
    }
    return $randstr;
}

/**
 * 产生唯一token 当做登陆凭证
 * @param string $namespace
 * @return string
 */
function createUniqid($namespace = '') {
    $guid = md5(uniqid('', true));
    return $guid;
}

function generatePwd($str)
{
    return md5($str);
}

