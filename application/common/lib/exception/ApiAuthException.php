<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/3
 * Time: 13:56
 */

namespace app\common\lib\exception;

class ApiAuthException extends BasicException
{
    public $errMessage = 'api 访问权限错误';
    public $httpCode = 401;
    public $statusCode = '';

}