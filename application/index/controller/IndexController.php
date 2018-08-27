<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;


use think\Controller;

class IndexController extends Controller
{
    public function hello(){
        $this->display();//加载模板文件，让模板呈现在浏览器中
    }

}