<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/9
 * Time: 9:54
 */

namespace app\api\controller\v1;


use think\Controller;


class Test extends Controller
{
    public $money = 0;

    private static $_instance = null;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();

        }
        return self::$_instance;
    }


    public function __clone()
    {
        return ;
    }

    public function test()
    {

    }
}