<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 21:07
 */

namespace app\common\lib\exception;


class BasicException extends \Exception
{
    public $errMessage = '服务器端错误';
    public $httpCode = 500;
    public $statusCode = '';


    public function __construct($data = [])
    {
        if (!is_array($data)) {
            throw new \Exception('自定义异常抛出错误');
        }

        if (array_key_exists('httpCode', $data)) {
            $this->httpCode = $data['httpCode'];
        }

        if (array_key_exists('statusCode', $data)) {
            $this->statusCode = $data['statusCode'];
        }

        if (array_key_exists('errMessage', $data)) {
            $this->errMessage = $data['errMessage'];
        }
    }
}