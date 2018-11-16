<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 10:59
 */

namespace app\api\controller\v1;


use think\Controller;

class Cat extends Controller
{
    public function index()
    {
        $lists = config('cat.lists');
        $lists = array_values($lists);
        array_unshift($lists, '首页');

        return show($lists);
    }
}