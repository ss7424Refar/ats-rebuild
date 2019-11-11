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

    /* @throws
     * check session
     */
    protected function checkSession(){

        if (null == Session::get('transToAts')){
            $this->error('Login Time Out', config('ats_sign_out_url'));
        }

        $this->loginUser = Session::get('transToAts');
//        if (null == $this->request->server('HTTP_REFERER')) {
//            $this->error('please click link from tpms', config('ats_sign_out_url'));
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
            $this->hasRight = true;
        }
        $this->assign('hasRight', $this->hasRight); // 给模板用

        // 给header.html中的变量赋值, index继承了common, index里面有fetch方法
        $this->assign([
            'description'=>$result[0]['description'], // 查看权限用的，不要删除
            'title'=> 'ATS',
        ]);

//        return $this->fetch('common/header',[]);
    }
    /*
     * 空操作
     */
    public function _empty(){
        // 导航栏的样式
        $this->assign('portCheck','');
        $this->assign('taskManager','');
        $this->assign('dashBoard','');

        return $this->fetch('common/404');
    }
}