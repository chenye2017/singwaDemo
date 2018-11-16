<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 11:38
 */

namespace app\common\model;


use think\Model;

class News extends Model
{
    public function getNewsByCondition($condition, $size = 10, $page = 1, $order = ['id'=>'desc'])
    {
        $res   = $this->field($this->_returnFiled())
            ->where($condition)
            ->order($order)
            ->limit($size)
            ->page($page)
            ->select();
        $count = $this->where($condition)
            ->count();
        return [$res, $count];
    }

    /**
     * 首页
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getIndexNews($num = 20)
    {
        $condition = [
            'status'      => 1,
            'is_position' => 1
        ];

        $res = $this->field($this->_returnFiled())
            ->where($condition)
            ->order('id', 'desc')
            ->limit($num)
            ->select();
        return $res;
    }

    /**
     * 头图
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHeaderNews($num = 5)
    {
        $condition = [
            'status'         => 1,
            'is_head_figure' => 1
        ];

        $res = $this->field($this->_returnFiled())
            ->where($condition)
            ->order('id', 'desc')
            ->limit($num)
            ->select();
        return $res;
    }

    private function _returnFiled()
    {
        return [
            'id',
            'catid',
            'image',
            'title',
            'read_count',
            'status',
            'is_position',
            'update_time',
            'create_time'
        ];
    }

}