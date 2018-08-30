<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;


use think\Controller;

class Index extends Controller
{
    public function hello(){
        return $this->fetch();//加载模板文件，让模板呈现在浏览器中
    }

    public function DynamicTaskDemo(){
//        return $this->fetch('common/footer');

        return $this->fetch();

    }


}