<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;

/*
 * 控制页面跳转
 */
class Index extends Common
{


    public function TaskManager(){
        return $this->fetch();

    }
    public function ToolAdd($id){
        $taskId = $this->request->param('selection');

        return $this->fetch();

    }

}