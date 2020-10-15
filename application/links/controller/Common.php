<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-26
 * Time: 上午9:51
 */

namespace app\links\controller;

use think\Controller;
use think\Cookie;
use think\Db;
use think\Log;

class Common extends Controller{
    // 是否为manager以上的权限
    public $hasRight = false;
    //登录用户
    public $loginUser = '';

    public function _initialize(){
        parent::_initialize();
        $this->checkCookie();
        $this->checkRight();
    }

    /* @throws
     * check cookie
     */
    protected function checkCookie(){

        if (config('session_debug')) {
            $this->loginUser = 'admin';
        } else {
            if (null == $this->request->server('HTTP_REFERER')) {
                $this->error('please click link from T system', config('links_sign_out_url'));
            } else {
                $userCookie = Cookie::get('TESTLINK_USER_AUTH_COOKIE');
                if (null == $userCookie) {
                    $this->error('no session find from T system', config('links_sign_out_url'));
                }
                $user = Db::table('users')
                            ->field('login')->where('cookie_string', $userCookie)->find();

                $this->loginUser = $user['login'];
                Log::record('hello! '. $this->loginUser);
            }

        }
    }
    /* @throws
     * check right
     */
    protected function checkRight(){
        $result = Db::table('users')->alias('t1')->where('t1.login', $this->loginUser)
            ->join('roles t2','t1.role_id = t2.id')->field('t1.login, t1.email, t2.description')->select();

        if ('admin' == $result[0]['description'] || 'leader' == $result[0]['description']
            || 'group leader' == $result[0]['description'] || 'manager' == $result[0]['description']){
            $this->hasRight = true;
        }
        // 由于有个新系统上线改了角色名称, 所以要加个判断
        if ('admin' == $result[0]['description'] || 'T-Manager' == $result[0]['description']
            || 'T-Leader' == $result[0]['description']){
            $this->hasRight = true;
        }

        $this->assign('hasRight', $this->hasRight); // 给模板用

        // 给header.html中的变量赋值, index继承了common, index里面有fetch方法
    }
    /*
     * 空操作
     */
    public function _empty(){
        return $this->fetch('common/404');
    }
}