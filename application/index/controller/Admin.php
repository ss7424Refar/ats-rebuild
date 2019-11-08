<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-16
 * Time: 下午6:44
 */

namespace app\index\controller;

use think\db;

/*
 * interface for ats 控制admin用户的页面跳转和server
 */

class Admin extends Common
{

    public function _initialize(){
        parent::_initialize();
        $this->assign('pushSocketUrl', config('pushman_web_socket'));
    }

    /*
     * http://localhost/ats/Index/Admin/startServer
     */
    public function startServer()
    {

        $cmd = "php " . ROOT_PATH . "server.php start > /dev/null 2>&1 &";
        $cmd2 = "php " . ROOT_PATH . "server_push.php start > /dev/null 2>&1 &";

        shell_exec($cmd);
        echo "start server". PHP_EOL;

        shell_exec($cmd2);
        echo "start server push";

    }

    /*
     * http://localhost/ats/Index/Admin/startServer
    */
    public function stopServer()
    {

        $cmd = "php " . ROOT_PATH . "server.php stop > /dev/null 2>&1 &";
        $cmd2 = "php " . ROOT_PATH . "server_push.php stop > /dev/null 2>&1 &";

        shell_exec($cmd);
        echo "stop server". PHP_EOL;

        shell_exec($cmd2);
        echo "stop server push";
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

        echo '<hr>';

        $cmd = "php " . ROOT_PATH . "server_push.php status";

        echo '<pre>';
        echo shell_exec($cmd);
        echo '</pre>';
    }

    public function SessionAnalyse() {
        // 导航栏的样式
        $this->assign('portCheck','');
        $this->assign('taskManager','');
        $this->assign('dashBoard','');
        $this->assign('bios','');

        return $this->fetch();

    }

    public function getAnalyseData() {
        $offset = $this->request->param('offset');
        $pageSize = $this->request->param('limit');
        $start = $this->request->param('start');
        $end = $this->request->param('end');

        $jsonResult = array();
        $result = null; $total= null;

        if (null != $start && null != $end) {
            $result = Db::query("select * from ats_session_analyse where `date` between ? and ? order by date limit ?, ?", [$start, $end, $offset, $pageSize]);
            $total = Db::query("select count(*) as t from ats_session_analyse where `date`  between ? and ? ", [$start, $end]);
            $jsonResult['total'] = $total[0]['t'];
        } else {
            $result = Db::table('ats_session_analyse')->order('date')->limit($offset, $pageSize)->select();
            $total = Db::table('ats_session_analyse')->count();
            $jsonResult['total'] = $total;
        }
        $jsonResult['rows'] = $result;

        return json($jsonResult); // 这里改成json()方法是因为bs-table@1.14.2接受json，而不是html类型
    }

    public function getSessionChart() {
        $start = $this->request->param('start');
        $end = $this->request->param('end');

        $result = Db::query("select * from ats_session_analyse");
        if (null != $start && null != $end) {
            $result = Db::query("select * from ats_session_analyse where `date` between ? and ? ", [$start, $end]);
        }

        $seriesData = array();
        $legendData = array();
        $selectedData = array();
        $optionResult = array();
        $saveData = array();

        for ($i = 0; $i < count($result); $i++) {
            $sessions = $result[$i]['sessions'];
            $json = json_decode($sessions, true);

            foreach($json as $key => $value){
                if (array_key_exists($key, $saveData)) {
                    $saveData[$key] = $saveData[$key] + $value;
                } else {
                    $saveData[$key] = $value;
                }
            }
        }
        // create option
        arsort($saveData); // 从大到小排序
        $i = 0;
        foreach($saveData as $name => $value){
            $legendData[] = $name;

            $seriesData[$i]['name'] = $name;
            $seriesData[$i]['value'] = $saveData[$name];
            // 初始化选中个数
            if ($i < 6) {
                $selectedData[$name] = true;
            } else {
                $selectedData[$name] = false;
            }
            $i++;
        }

        $optionResult['legendData'] = $legendData;
        $optionResult['seriesData'] = $seriesData;
        $optionResult['selectedData'] = $selectedData;

        if (!empty($seriesData)) {
            return json_encode($optionResult);
        } else {
            return 'No Data';
        }
    }
}