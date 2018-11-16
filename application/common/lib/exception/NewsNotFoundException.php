<?php

namespace app\common\lib\exception;

class NewsNotFoundException extends BasicException
{
    public $httpCode = 404;
    public $errMessage = '文章不存在';
    public $statusCode = '';
}