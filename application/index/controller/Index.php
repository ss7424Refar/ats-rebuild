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

class Index extends Controller
{
    public function hello(){
        $this->checkSession();

    }

    public function DynamicTaskDemo(){

        return $this->fetch();

    }
    /*
     * HTTP_REFERER
     */
//    public function test(){
//        $info = Request::instance()->server('HTTP_REFERER');
//        var_dump($info);
//    }


    public function test(){
        return url('index/AddTool/createHtmlElement');

    }

    /*
     * check session
     */
    protected function checkSession(){
        if (!Session::has('transToAts')){
            $this->success('哈哈哈！', 'Index/DynamicTaskDemo');

        }

    }


}