<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/3
 * Time: 12:57
 */

namespace app\api\controller;

use app\common\lib\Jpush;
use app\common\model\UserNews;


use app\common\lib\exception\ApiAuthException;
use think\Exception;
use think\Validate;

class Test
{
    public function test()
    {
        /*$userid = 8;
        $news = [1, 2,3];
        $upvoteInfo = UserNews::all(['news_id' => ['in', $news], 'user_id' => $userid]);
        $data = [];
        var_dump(count($upvoteInfo));exit;*/
        $jpush = new Jpush();
        $jpush->init();

        $a = new Validate([
           'name' =>  'required',
            'email' => 'email'
        ]);

    }
}