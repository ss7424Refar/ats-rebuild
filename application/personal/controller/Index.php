<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\personal\controller;

use think\Controller;

/*
 * 控制页面跳转
 */
class Index extends Controller
{

    public function SetUsers(){

        return $this->fetch();
    }

    public function WebScan(){
        $users = $this->request->param('users');

        $this->assign('users', $users);

        return $this->fetch();

    }

}