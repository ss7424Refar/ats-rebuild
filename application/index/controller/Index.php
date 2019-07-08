<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-27
 * Time: 下午7:27
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use think\Session;

/*
 * 控制页面跳转
 */
class Index extends Common
{

    public function _initialize(){
        parent::_initialize();
        $this->assign('pushSocketUrl', config('pushman_web_socket'));
    }

    public function DashBoard(){

        // 导航栏的样式
        $this->assign('dashBoard','active');
        $this->assign('taskManager','');
        $this->assign('portCheck','');
        return $this->fetch();

    }

    public function TaskManager(){
        // 导航栏的样式
        $this->assign('taskManager','active');
        $this->assign('dashBoard','');
        $this->assign('portCheck','');
        return $this->fetch();

    }

    public function PortCheck(){
        // 导航栏的样式
        $this->assign('portCheck','active');
        $this->assign('taskManager','');
        $this->assign('dashBoard','');

        // web socket url
        $this->assign('webSocketUrl',config('workman_web_socket'));

        return $this->fetch();

    }

    public function Document(){
        // 获取URL访问的ROOT地址
        $rootPath = $this->request->root(true);
        $this->redirect($rootPath. '/public/pdf/web/viewer.html?file=Manual.pdf');
    }

    public function ReleaseNote(){
        // 导航栏的样式
        $this->assign('portCheck','');
        $this->assign('taskManager','');
        $this->assign('dashBoard','');

        return $this->fetch();
    }

    public function ToolAdd(){
        $this -> ReferenceCheck();
        $taskId = $this->request->param('taskId');

        // 在spinner回车的时候会有个bug
        if (empty($taskId)) {
            $this->error('please click link from taskManager', url('Index/TaskManager'));
        }

        $this->assign('taskManager','active');
        $this->assign('dashBoard','');
        $this->assign('portCheck','');

        // 模板变量赋值
        $this->assign('taskId',$taskId);
        $this->assign('ipCheck',  $this -> ipCheck($taskId));

        return $this->fetch();

    }

    public function ToolEdit(){
        $this -> ReferenceCheck();
        $taskId = $this->request->param('taskId');

        // 在spinner回车的时候会有个bug
        if (empty($taskId)) {
            $this->error('please click link from taskManager', url('Index/TaskManager'));
        }

        $this->assign('taskManager','active');
        $this->assign('dashBoard','');
        $this->assign('portCheck','');

        // 模板变量赋值
        $this->assign('taskId', $taskId);
        $this->assign('ipCheck',  $this -> ipCheck($taskId));

        return $this->fetch();

    }

    public function SignOut(){
        Session::flush();
        $this->redirect(config('ats_sign_out_url'));
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

    /*
     * ip = 192.168.0.40 check
     * if equal 40 return upstairs
     */

    private function ipCheck($taskId) {
        $ip = Db::query('select lan_ip from ats_task_basic where task_id = ? ', [$taskId]);
        // 当在spinner回车时候会有个bug, 页面会刷新
        if (!empty($ip)) {
            $tmp = explode('.', $ip[0]['lan_ip']);
            return 40 == $tmp[3] ? 'up' : 'down';
        }
        return 'down';
    }
}