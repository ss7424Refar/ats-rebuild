<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;

use think\Request;

/*
 * 控制页面跳转
 */
class Index extends Common
{
    public function DashBoard(){
        return $this->fetch();

    }
    public function TaskManager(){
        return $this->fetch();

    }
    public function ToolAdd(){
        $this -> ReferenceCheck();
        $taskId = $this->request->param('taskId');

        // 模板变量赋值
        $this->assign('taskId',$taskId);

        return $this->fetch();

    }

    public function ToolEdit(){
        $this -> ReferenceCheck();
        $taskId = $this->request->param('taskId');

        // 模板变量赋值
        $this->assign('taskId',$taskId);

        return $this->fetch();

    }
    /*
     * 防止浏览器恶意输入
     */
    private function ReferenceCheck() {
        if (null == Request::instance()->server('HTTP_REFERER')){
            $this->error('please click link from taskManager', url('Index/TaskManager'));
            return;
        }
    }
}