<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Request;

/*
 * 控制页面跳转
 */
class Index extends Controller
{

    public function _initialize()
    {
        parent::_initialize();
        $this->checkSession();
    }


    public function TaskManager(){
        return $this->fetch();

    }
    public function DynamicTaskDemo(){

        return $this->fetch('common/404');

    }

    /*
     * check session
     */
    protected function checkSession(){
//        if (!Session::has('transToAts') || null == Request::instance()->server('HTTP_REFERER')){
//            $this->error('Login Time Out', 'http://172.30.52.43/tpms/index.php');
//
//        }

    }


}