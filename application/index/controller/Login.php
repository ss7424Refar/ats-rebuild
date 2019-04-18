<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-4-18
 * Time: 上午9:25
 */

namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Db;
use think\Session;

class Login extends Controller {
    public function check(){
        if (config('session_debug')) {
            Session::set('transToAts','admin');
            $this->redirect('index/DashBoard');
        } else {
            if (null == $this->request->server('HTTP_REFERER')) {
                $this->error('please click link from tpms', config('ats_sign_out_url'));
            } else {
                $userCookie = Cookie::get('TESTLINK_USER_AUTH_COOKIE');
                if (null == $userCookie) {
                    $this->error('no session find from tpms', config('ats_sign_out_url'));
                }
                // 跳转到首页
                $user = Db::table('users')->field('login')->where('cookie_string', $userCookie)->find();
                Session::set('transToAts', $user['login']);
                $this->redirect('index/dashboard');
            }

        }

    }

}