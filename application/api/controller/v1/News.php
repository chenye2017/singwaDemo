<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/7
 * Time: 14:19
 */

namespace app\api\controller\v1;


use app\api\validate\BaseValidate;
use app\common\lib\exception\BasicException;
use app\common\lib\exception\NewsNotFoundException;
use app\common\model\Comment;
use app\common\model\UserNews;
use app\common\model\User as UserModel;
use think\Db;
use think\Log;
use think\Request;
use app\common\model\News as NewsModel;
use think\Validate;

class News extends BasicAuth
{
    /**
     * 新闻列表页
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function index()
    {
        (new BaseValidate(['catid'=>'required']))->goCheck();
        $catid = Request::instance()->get('catid');
        $page = Request::instance()->get('page', 1);
        $size = Request::instance()->get('size', 10);
        $news = new NewsModel();
        $condition = [
            'status' => 1,
            'catid' => $catid
        ];
        $res = $news->getNewsByCondition($condition, $size, $page);

        $news = [];
        foreach ($res[0] as $rKey=>$rValue) {
            array_push($news, $rValue['id']);
        }
        $upvoteInfo = $this->checkUpvote($this->user['id'],$news);
        foreach ($res[0] as $key=>$value) {
            $res[0][$key]['isupvote'] = $upvoteInfo[$value['id']] ?? 0;
        }

        $res = [
            'count' => $res[1],
            'page' => ceil($res[1]/$size),
            'data' => $res[0]
        ];
        return show($res);
    }

    /**
     * 新闻搜索
     * @return \think\response\Json
     */
    public function search()
    {
        $page = Request::instance()->get('page', 1);
        $size = Request::instance()->get('size', 10);
        $title = Request::instance()->get('title');
        $news = new NewsModel();
        $condition = [
            'status' => 1,
            'title' => ['like', '%'.$title.'%']
        ];
        $res = $news->getNewsByCondition($condition, $size, $page);
        $res = [
            'count' => $res[1],
            'page' => ceil($res[1]/$size),
            'data' => $res[0]
        ];
        return show($res);
    }

    /**
     * 新闻排行
     * @return \think\response\Json
     */
    public function rank()
    {
        $page = Request::instance()->get('page', 1);
        $size = Request::instance()->get('size', 10);
        $news = new NewsModel();
        $condition = [
            'status' => 1
        ];
        $order = ['read_count' => 'desc'];
        $res = $news->getNewsByCondition($condition, $size, $page, $order);
        $res = [
            'count' => $res[1],
            'page' => ceil($res[1]/$size),
            'data' => $res[0]
        ];
        return show($res);
    }

    /**
     * 新闻详情
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info()
    {
        $id = Request::instance()->route('id');
        $news = new NewsModel();
        $news->where(['id'=> $id])->setInc('read_count');
        $res = $news->find($id);
        return show($res);
    }

    /**
     * 点赞
     * @return \think\response\Json
     * @throws BasicException
     */
    public function upvote()
    {
        $id = Request::instance()->post('newsid');

        // 验证id 是否正确

        // 验证文章是否存在
        $newsInfo = NewsModel::get(['id'=>$id, 'status'=>1]);
        if (!$newsInfo) {
            throw new NewsNotFoundException([]);
        }

        // 是否已经点赞过
        $upvoteInfo = UserNews::get(['user_id'=>$this->user['id'], 'news_id'=>$id]);
        if ($upvoteInfo) {
            throw new BasicException([
               'httpCode' => 400,
                'message' => '已经点赞过了',
                'statusCode' => ''
            ]);
        }

        Db::startTrans();
        try {
            $data = [
                'user_id' => $this->user['id'],
                'news_id' => $id
            ];
            UserNews::create($data);
            Db::table('ent_news')->where(['id'=>$id])->setInc('upvote_count', 1);
            DB::commit();
        }catch (\Exception $e) {
            Db::rollback();
            Log::record($e->getMessage(), 'error');
            throw new BasicException([
               'httpCode' => 500,
               'message' => '点赞失败',
               'statusCode' => ''
            ]);
        }

        return show([]);
    }

    /**
     * 取消点赞
     * @return \think\response\Json
     * @throws BasicException
     * @throws NewsNotFoundException
     * @throws \think\exception\DbException
     */
    public function cancelUpvote()
    {
        $id = Request::instance()->post('newsid');

        // 验证id 是否正确

        // 验证文章是否存在
        $newsInfo = NewsModel::get(['id'=>$id, 'status'=>1]);
        if (!$newsInfo) {
            throw new NewsNotFoundException([]);
        }

        // 是否点赞过
        $upvoteInfo = UserNews::get(['user_id'=>$this->user['id'], 'news_id'=>$id]);
        if (!$upvoteInfo) {
            throw new BasicException([
                'httpCode' => 400,
                'message' => '还没有点赞',
                'statusCode' => ''
            ]);
        }

        Db::startTrans();
        try {
            UserNews::destroy(['user_id'=>$this->user['id'], 'news_id'=>$id]);
            Db::table('ent_news')->where(['id'=>$id])->setInc('upvote_count', -1);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            Log::record($e->getMessage(), 'error');
            throw new BasicException([
                'httpCode' => 500,
                'message' => '取消点赞失败',
                'statusCode' => ''
            ]);
        }

        return show([]);
    }

    /**
     * 根据id，获取用户是否点赞
     * @param $userid
     * @param $news
     * @return array
     * @throws \think\exception\DbException
     */
    public function checkUpvote($userid, $news)
    {
        $upvoteInfo = UserNews::all(['news_id' => ['in', $news], 'user_id' => $userid]);
        $data = [];

        foreach ($upvoteInfo as $uKey=>$uValue) {
            $data[$uValue['news_id']] = 1;
        }
        return $data;
    }

    /**
     * 获取文章评论内容
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function comment()
    {
        $id = Request::instance()->route('id');
        $page = Request::instance()->get('page', 1);
        $size = Request::instance()->get('size', 10);

        $commentsCount = Comment::where(['news_id'=>$id, 'status'=>1])->count();

        // 一共多少页
        $pageCount = ceil($commentsCount / $size);
        // 评论的内容处理
        $commets = Comment::where(['news_id'=>$id, 'status'=>1])->page($page)->limit($size)->select();

        $userArr = [];
        foreach ($commets as $cKey=>$cValue) {
            if ($cValue['to_user_id']) {
                array_push($userArr, $cValue['to_user_id']);
            }
            array_push($userArr, $cValue['user_id']);
        }
        $userArr = array_unique($userArr);

        $userList = $this->getUserInfo($userArr);
        foreach ($commets as $key=>$value) {
            $commets[$key]['username'] = $userList[$value['user_id']] ?? '';
            $commets[$key]['to_username'] = $userList[$value['to_user_id']] ?? '';
        }

        return show(['page'=>$pageCount, 'data'=>$commets]);
    }

    /**
     * 根据userid, 获取用户名
     * 还可以扩展，in之后的数据可以是一个id 对应一个数组，方便获取除了用户名之外的数据
     * @param $userArr
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserInfo($userArr)
    {
        if (!$userArr) {
            return [];
        }

        $userinfo = UserModel::where(['id'=>['in', $userArr]])->select();
        $data = [];
        foreach ($userinfo as $uKey=>$uValue) {
            $data[$uValue['id']] = $uValue['username'];
        }
        return $data;
    }

    /**
     * 原生方法获取查询数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function joinComment()
    {
        $id = Request::instance()->route('id');
        $page = Request::instance()->get('page', 1);
        $size = Request::instance()->get('size', 10);

        $pageCount = Comment::where(['news_id'=>$id, 'status'=>1])->count();
        $pageCount = ceil($pageCount / $size);

        $comments = Db::table('ent_comment')->alias('c')->field('c.id,c.create_time,c.content, u.username as commentUser, e.username as replyUser')->join('ent_user u', 'c.user_id = u.id', 'LEFT')->join('ent_user e', 'c.to_user_id = e.id', 'LEFT')->where(['c.status'=>1,'e.status'=>1])->page($page)->limit($size)->select();
        return show(['count'=>$pageCount, 'data'=>$comments]);
    }
}