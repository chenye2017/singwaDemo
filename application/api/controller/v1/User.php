<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/9
 * Time: 16:34
 */

namespace app\api\controller\v1;


use app\common\lib\exception\BasicException;
use think\Request;
use app\common\model\User as UserModel;

class User extends BasicAuth
{
    public function index()
    {
        $res['username'] = $this->user['username'];
        $res['phone'] = $this->user['phone'];
        return show($res);
    }

    public function update($id)
    {
        $user = UserModel::get(['id'=>$id]);
        $username = Request::instance()->put('username');
        $sex = Request::instance()->put('sex',1);
        $password = generatePwd(Request::instance()->put('pwd'));
        $data = [

            'username' => $username,
            'sex' => $sex,
            'password' => $password
        ];

        $other = UserModel::get(['username' => $username, 'status' => 1, 'id' => ['<>', $id]]);

        if ($other) {
            throw new BasicException([
                'httpCode' => 400,
                'message' => '用户名已存在',
                'statusCode' => ''
            ]);
        }

        $user->save($data);

        return show([]);
    }
}