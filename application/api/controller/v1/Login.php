<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2018/11/9
 * Time: 13:49
 */

namespace app\api\controller\v1;


use app\common\lib\ali\demo\AliSms;
use app\common\lib\exception\BasicException;
use app\common\model\User;
use think\Log;
use think\Request;
use think\Cache;

class Login extends Common
{
    /**
     * 用来发送验证码
     * @return \think\response\Json
     */
    public function sendCode()
    {
        $phone = Request::instance()->get('phone');

        // 产生验证码
        $code = getRandStr(6);

        // 存入缓存中用来进行登陆验证
        Cache::set((config('cachetime.prefix'))['login'] . $phone, $code, (config('cachetime'))['login_code_time']);


        // todo 发送短信
        $sms = new AliSms();
        $content = $sms->sendSms($phone, $code);
        $content = json_decode(json_encode($content), true);
        // 发送失败
        if ($content['Code'] != 'OK') {
            Log::error(implode(',', $content));
            throw new BasicException();
        }

        return show([]);
    }

    /**
     * 用来进行验证码登陆
     * @return \think\response\Json
     * @throws BasicException
     */
    public function index()
    {
        $phone = Request::instance()->post('phone');
        $code  = Request::instance()->post('code');

        // 验证码登陆逻辑
        $cache = Cache::get((config('cachetime.prefix'))['login'] . $phone);

        if (!$cache || $code != $cache) {
            $data = [
                'message'    => '验证码无效',
                'httpCode'   => 400,
                'statusCode' => ''
            ];
            throw new BasicException($data);
        }
        $user = User::get(['phone' => $phone, 'status' => 1]);

        $token   = createUniqid();
        $timeOut = time() + config('api.token_time');

        if ($user) {
            // 修改用户token
            $data = [
                'token'    => $token,
                'time_out' => $timeOut
            ];
            $user->save($data);
            $firstLogin = 0;
        } else {
            // 注册用户
            $data = [
                'username' => '语法糖_' . $phone,
                'phone'    => $phone,
                'token'    => $token,
                'time_out' => $timeOut,
                'image'    => '测试图片',
                'sex'      => 1,
                'status'   => 1
            ];
            User::create($data);
            $firstLogin = 1;
        }

        return show([
            'token'       => $token,
            'first_login' => $firstLogin
        ]);
    }

    /**
     * 用户名密码登陆
     * @return \think\response\Json
     * @throws BasicException
     * @throws \think\exception\DbException
     */
    public function usernameLogin()
    {
        $username = Request::instance()->post('username');
        $pwd      = Request::instance()->post('pwd');

        $user = User::get(['username' => $username, 'status' => 1]);

        // 没有找到，直接返回错误，这种方式不能登陆
        if (!$user) {
            throw new BasicException([
                'httpCode'   => 400,
                'message'    => '用户名不存在',
                'statusCode' => ''
            ]);
        }

        $password = generatePwd($pwd);
        if ($password != $user['password']) {
            throw new BasicException([
                'httpCode'   => 400,
                'message'    => '密码错误',
                'statusCode' => ''
            ]);
        }

        $token   = createUniqid();
        $timeOut = time() + config('api.token_time');


        // 修改用户token
        $data = [
            'token'    => $token,
            'time_out' => $timeOut
        ];
        $user->save($data);
        $firstLogin = 0;


        return show([
            'token'       => $token,
            'first_login' => $firstLogin
        ]);
    }

}