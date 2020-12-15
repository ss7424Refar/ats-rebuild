<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 20-1-19
 * Time: 下午3:40
 */

namespace app\links\controller;

use think\Request;
use think\Db;

class Jump extends Common {
    public function DashBoard(){
        return $this->fetch();
    }

    public function TaskManager(){
        return $this->fetch();
    }

    public function ToolAdd(){
        $this -> ReferenceCheck();
        $taskId = $this->request->param('taskId');

        // 在spinner回车的时候会有个bug
        if (empty($taskId)) {
            $this->error('please click link from taskManager', url('Jump/TaskManager'));
        }

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
            $this->error('please click link from taskManager', url('Jump/TaskManager'));
        }

        // 模板变量赋值
        $this->assign('taskId', $taskId);
        $this->assign('ipCheck',  $this -> ipCheck($taskId));

        return $this->fetch();
    }


    public function PortCheck(){
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
        return $this->fetch();
    }

    public function Setting(){
        return $this->fetch('setting/common_tool');
    }

    public function Bios(){
        return $this->fetch('setting/bios_package');
    }

    /*
 * 防止浏览器恶意输入
 */
    private function ReferenceCheck() {
        if (null == Request::instance()->server('HTTP_REFERER')){
            $this->error('please click link from taskManager', url('Jump/TaskManager'));
            return;
        }
    }

    /*
     * ip = 192.168.0.40 check
     * if equal 40 return upstairs
     */

    private function ipCheck($taskId) {
//        $ip = Db::query('select lan_ip from ats_task_basic where task_id = ? ', [$taskId]);
//        // 当在spinner回车时候会有个bug, 页面会刷新
//        if (!empty($ip)) {
//            $tmp = explode('.', $ip[0]['lan_ip']);
//            return 40 == $tmp[3] ? 'up' : 'down';
//        }
        return 'down';
    }
}