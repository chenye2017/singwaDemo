<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 20:32
 */

namespace app\api\controller\v1;


use app\common\lib\AES;
use app\common\lib\exception\ApiAuthException;
use think\Controller;
use think\Request;


class Common extends Controller
{
    public $header;

    public function _initialize()
    {
        $this->header = Request::instance()->header();
        if (!config('app_debug')) {
            $this->checkRequestAuth();   // 检测请求是否合法
        }
    }

    public function checkRequestAuth()
    {
        $a = $this->header['app_type'] ?? '';
        $v = $this->header['version'] ?? '';
        $d = $this->header['did'] ?? '';
        $s = $this->header['sign'] ?? '';


        if (!$a || !$v || !$d || !$s) {
           throw new ApiAuthException();
        }

        // 检测aes
        $aesConfig = config('api.aes');
        $aes = new AES($aesConfig['key'], $aesConfig['vi']);

        if ($aes->myDecrypt($s) != 'hello cy') {
            throw new ApiAuthException();
        }

    }
}