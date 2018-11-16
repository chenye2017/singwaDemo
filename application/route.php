<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('api/:version/cat', 'api/:version.Cat/index'); // 获取分类


Route::get('api/:version/index', 'api/:version.Index/index'); // 获取首页

Route::get('api/:version/news$', 'api/:version.News/index'); // 某个分类数据

Route::get('api/:version/news/search', 'api/:version.News/search'); // 搜索

Route::get('api/:version/news/rank', 'api/:version.News/rank'); // rank 排行榜

Route::get('api/:version/news/:id', 'api/:version.News/info', [], ['id'=>'\d+']);

Route::get('api/:version/version$', 'api/:version.Version/init'); // 版本检测

Route::get('api/:version/version/test', 'api/:version.Version/test'); // 版本检测

Route::get('api/:version/login/sendCode', 'api/:version.Login/sendCode'); // 发送手机验证码

Route::post('api/:version/login$', 'api/:version.Login/index'); // 手机验证码登陆

Route::post('api/:version/usernamelogin', 'api/:version.Login/usernameLogin'); // 手机验证码登陆

Route::post('api/:version/user$', 'api/:version.User/index'); // 获取用户信息

Route::put('api/:version/user/:id', 'api/:version.User/update'); // 获取用户信息

Route::post('api/:version/news/upvote', 'api/:version.News/upvote'); // 新闻点赞

Route::post('api/:version/news/cancelupvote', 'api/:version.News/cancelUpvote'); // 新闻点赞

Route::get('api/:version/news/comment/:id', 'api/:version.News/comment'); // 获取新闻评论

Route::get('api/:version/news/joincomment/:id', 'api/:version.News/joinComment'); // 原生方法获取评论

Route::get('api/:version/newscomment', 'api/:version.news_comment/test'); // 原生方法获取评论