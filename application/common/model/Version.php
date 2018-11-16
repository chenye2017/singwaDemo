<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 19:53
 */

namespace app\common\model;


use think\Model;

class Version extends Model
{
    public function getVersion($where, $order)
    {
        $res = $this->where($where)
            ->order($order)
            ->find();
        return $res;
    }
}