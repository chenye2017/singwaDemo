<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 19:52
 */

namespace app\api\controller\v1;

use app\common\model\AppActive;
use app\common\model\Version as VersionModel;

use think\Request;

class Version extends Common
{
    public function init()
    {
        $h = Request::instance()->header();
        $v = $h['version'];
        $a = $h['app_type'];
        $d = $h['did'];

        $version = new VersionModel();
        $condition = [
            'status' => 1,
            'app_type' => $a
        ];
        $order = [
            'id' => 'desc'
        ];
        $res = $version->getVersion($condition, $order);

        if ($v == $res['version']) {
            $res['is_update'] = 0;

        } elseif ($v < $res['version']) {
            if ($res['is_force'] == 1) {
                $res['is_update'] = 2;
            } else {
                $res['is_update'] = 1;
            }
        }

        // 增加一条记录这个用户
        $data = [
            'version' => $v,
            'app_type' => $a,
            'did' => $d,
        ];
        (new AppActive())->save($data);

        return show($res);
    }

    public function test()
    {
        $test = Test::getInstance();
        $test->money = 2;
        $test2 = $this->test2();
        var_dump($test->money, $test2);
    }

    public function test2()
    {
        $test = Test::getInstance();
        return $test->money;
    }


}