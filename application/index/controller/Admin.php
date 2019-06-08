<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-16
 * Time: 下午6:44
 */

namespace app\index\controller;

use think\Controller;
use think\db;

/*
 * interface for ats 控制admin用户的页面跳转和server
 */

class Admin extends Common
{
    /*
     * http://localhost/ats/Index/Admin/startServer
     */
    public function startServer()
    {

        $cmd = "php " . ROOT_PATH . "server.php start > /dev/null 2>&1 &";
        shell_exec($cmd);

        return "start server";
    }

    /*
     * http://localhost/ats/Index/Admin/startServer
    */
    public function stopServer()
    {

        $cmd = "php " . ROOT_PATH . "server.php stop > /dev/null 2>&1 &";

        shell_exec($cmd);

        return "stop server";
    }

    /*
     * http://localhost/ats/Index/Admin/getStatus
    */
    public function getStatus()
    {

        $cmd = "php " . ROOT_PATH . "server.php status";

        echo '<pre>';
        echo shell_exec($cmd);
        echo '</pre>';
    }

    public function SessionAnalyse() {
        // 导航栏的样式
        $this->assign('portCheck','');
        $this->assign('taskManager','');
        $this->assign('dashBoard','');

        return $this->fetch();

    }

    public function getAnalyseData() {
        $search = $this->request->param('search');
        $offset = $this->request->param('offset');
        $pageSize = $this->request->param('pageSize');

        $jsonResult = array();

        $result = Db::table('ats_session_analyse')->orderRaw('str_to_date(date,\'%Y-%m-%d\')')->limit($offset, $pageSize)->select();
        $total = Db::table('ats_session_analyse')->count();

        $jsonResult['total'] = $total;
        $jsonResult['totalNotFiltered'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);


    }
}