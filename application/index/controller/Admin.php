<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-16
 * Time: 下午6:44
 */

namespace app\index\controller;

use think\Controller;

/*
 * interface for ats
 */

class Admin extends Controller
{
    /*
     * http://localhost/ats/Index/Admin/startServer
     */
    public function startServer()
    {

        $cmd = "sudo php " . ROOT_PATH . "server.php start > /dev/null 2>&1 &";
        shell_exec($cmd);

        return "start server";
    }

    /*
     * http://localhost/ats/Index/Admin/startServer
    */
    public function stopServer()
    {

        $cmd = "sudo php " . ROOT_PATH . "server.php stop > /dev/null 2>&1 &";

        shell_exec($cmd);

        return "stop server";
    }

    /*
     * http://localhost/ats/Index/Admin/getStatus
    */
    public function getStatus()
    {

        $cmd = "sudo php " . ROOT_PATH . "server.php status";

        echo '<pre>';
        echo shell_exec($cmd);
        echo '</pre>';
    }
}