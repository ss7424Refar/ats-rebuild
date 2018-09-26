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

    public function _initialize(){
        parent::_initialize();
        $this->checkSession();
        $this->checkRight();
    }

    /*
     * check session
     */
    protected function checkSession(){
        Session::set('transToAts','admin');
//        if (!Session::has('transToAts') || null == Request::instance()->server('HTTP_REFERER')){
//            $this->error('Login Time Out', 'http://172.30.52.43/tpms/index.php');
//
//        }

    }
    /*
     * check right
     */
    public function checkRight(){
        $user = Session::get('transToAts');

        $result = Db::table('users')
            ->alias('t1')
            ->join('roles t2','t1.role_id = t2.id')
            ->where('t1.login', $user)
            ->field('t1.login, t1.email, t2.description ')
            ->select();

        if ('admin' == $result[0]['description'] || 'leader' == $result[0]['description'] || 'group leader' == $result[0]['description'] || 'manager' == $result[0]['description']){
//            $this->assign('hasRight', true); // 给模板用
            $this->hasRight = true;
        }

    }
    /*
     * 空操作
     */
    public function _empty(){
        return $this->fetch('common/404');
    }
}