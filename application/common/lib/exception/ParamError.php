<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/15
 * Time: 18:28
 */

namespace app\common\lib\exception;


class ParamError extends BasicException
{
    public $httpCode = 400;
    public $statusCode = '';
    public $errMessage = '参数错误';
}