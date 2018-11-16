<?php

namespace app\admin\controller;

use app\common\model\User;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }

    public function add()
    {
        $request = Request::instance();
        if ($request->isGet()) {
            return $this->fetch();
        } else {
            $user = new User();
            $user->username = $request->param('username');
            $user->password = md5($request->param('password'));
            $user->save();
            $id = $user->id;
            return response('success'.' '.$id);
        }
    }

}