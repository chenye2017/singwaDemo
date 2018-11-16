<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/3
 * Time: 13:28
 */

namespace app\common\lib\exception;



use think\exception\Handle;


class ApiHandle extends Handle
{
    public $httpCode = 500;

    public function render(\Exception $e)
    {
        if ($e instanceof BasicException) {
            return show([], $e->statusCode, $e->errMessage, $e->httpCode);

        } else {
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                // 这个是代码bug，需要记录错误
                return show([], 99999, '未知错误', 500);
            }
        }
    }
}