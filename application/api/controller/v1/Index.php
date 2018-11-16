<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 11:49
 */

namespace app\api\controller\v1;


use app\common\model\News;
use think\Controller;
use think\Request;

class Index extends Controller
{
    /**
     * 首页需要数据
     * @return \think\response\Json
     */
    public function index()
    {
        $news = new News();
        $index = $news->getNewsByCondition(['status'=>1, 'is_head_figure'=>1], 20);
        $index[0] = $this->handleData($index[0]);
        $head = $news->getNewsByCondition(['status'=>1, 'is_position'=>1], 5);
        $head[0] = $this->handleData($head[0]);
        $data = [
            'head' => [
                'count' => $head[1],
                'page' => ceil($head[1]/5),
                'res' => $head[0]
            ],
            'index' => [
                'count' => $index[1],
                'page' => ceil($index[1]/20),
                'res' => $index[0]
            ]
        ];
        return show($data);
    }

    public function handleData($data)
    {
        $lists = config('cat.lists');
        foreach ($data as $dKey => $dValue) {
            $data[$dKey]['cat'] = $lists[$dValue['catid']];
        }
        return $data;
    }
}