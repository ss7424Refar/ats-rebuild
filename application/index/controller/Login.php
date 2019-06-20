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
use think\Log;

class Login extends Controller {
    public function check(){
        if (config('session_debug')) {
            Session::set('transToAts','admin');
            // 用于分析登录人数
            $this->sessionAnalyse();
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
                Log::record('hello '. $user['login']);
                $this->sessionAnalyse();
                $this->redirect('index/dashboard');
            }

        }
    }

    private function sessionAnalyse() {
        $today = date('Y-m-d', time());
        $user = Session::get('transToAts');

        $result = Db::table('ats_session_analyse')->where('date', $today)->find();

        // 某个人第一次进入
        if (empty($result)) {
            Db::table('ats_session_analyse');

            $json = json_encode(Array($user => 1));

            $data = ['date' => $today, 'sessions' => $json];
            Db::table('ats_session_analyse')->insert($data);
        } else {
            $array = json_decode($result['sessions'], true);

            if (array_key_exists($user, $array)) {
                $array[$user] = $array[$user] + 1;
            } else {
                $array[$user] = 1;
            }

            $json = json_encode($array);
            Db::table('ats_session_analyse')->where('date', $today)->update(['sessions' => $json]);

        }

    }
}