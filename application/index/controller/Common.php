<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-26
 * Time: 上午9:51
 */

namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Db;

class Common extends Controller{
    // 是否为manager以上的权限
    public $hasRight = false;
    //登录用户
    public $loginUser = '';

    public function _initialize(){
        parent::_initialize();
        $this->checkSession();
        $this->checkRight();
    }

    /*
     * check session
     */
    protected function checkSession(){
        Session::set('transToAts','Zhao Tianer');
        $this->loginUser = Session::get('transToAts');
//        if (!Session::has('transToAts') || null == Request::instance()->server('HTTP_REFERER')){
//            $this->error('Login Time Out', 'http://172.30.52.43/tpms/index.php');
//
//        }

    }
    /* @throws
     * check right
     */
    public function checkRight(){
        $user = Session::get('transToAts');

        $result = Db::table('users')->alias('t1')->where('t1.login', $user)
            ->join('roles t2','t1.role_id = t2.id')->field('t1.login, t1.email, t2.description ')->select();

        if ('admin' == $result[0]['description'] || 'leader' == $result[0]['description'] || 'group leader' == $result[0]['description'] || 'manager' == $result[0]['description']){
//            $this->assign('hasRight', true); // 给模板用
            $this->hasRight = true;
        }
        // 给header.html中的变量赋值, index继承了common, index里面有fetch方法
        $this->assign([
            'login'=>$result[0]['login'],
            'email'=>$result[0]['email'],
            'description'=>$result[0]['description'],
            'title'=> 'Automation Test System'
        ]);
//        return $this->fetch('common/header',[]);
    }
    /*
     * 空操作
     */
    public function _empty(){
        return $this->fetch('common/404');
    }
}