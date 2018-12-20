<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-12
 * Time: 上午9:11
 */


namespace app\services\controller;

use think\Controller;
use think\Db;
use ext\MailerUtil;

/*
 * interface for ats
 */
class SendMail extends Controller {
    private $today;

    public function _initialize(){
        parent::_initialize();

        $this->today = date("Y-m-d");
    }

    /** baseline function
     * @return mixed
     */
    public function sendBaseLine() {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');

        $emailTo = $this->getUserAddress($taskId);

        $info = $this->getTaskStepsInfo($taskId, $steps);

        $mailTitle = '[ATS][' . $this->today . ']'. $info[0]['tool_name'] .'You Need to run the baseline image';

        $content = '<html>' .
            '	<head>' .
            '		<style type="text/css">' .
            '			p {margin: 5px;font-size: 13px;}' .
            '			th {' .
            '				font-size: 12px;' .
            '			}' .
            '		    table tr td {' .
            '				align:center;' .
            '				border: thin dotted #96B97D;' .
            '				font-size:12px;' .
            '				text-align: center;' .
            '			}' .
            '			th {padding: 6px;}' .
            '		</style>' .
            '	</head>' .
            '	<body>' .
            '		<p>Dear ' . $info[0]['tester'] . ',</p>' .
            '		<p>Since OEM image test result is NOT passed the target metrics, please find your target machine on test shelf and click OK to start running baseline image.</p>' .
            '		<p style="font-size:12px;color:red"><i>The Jumpstart task as below.</i></p>' .
            '	<table>' .
            '		<tr bgcolor="#FAF2CC">' .
            '			<th>TaskID</th><th>Machine ID</th><th>Test Machine</th><th>Assigned Task</th>' .
            '			<th>Test Result</th><th>Start Date</th><th>Finish Date</th>' .
            '		</tr>' .
            '       <tr>' .
            '			<td>ATS_' . $info[0]['task_id'] . '</td>' .
            '			<td>' . $info[0]['machine_id'] . '</td>' .
            '			<td>' . $info[0]['machine_name'] . '</td>' .
            '			<td>JumpStart</td>' .
            '			<td>' . $info[0]['status'] . '</td>' .
            '			<td>' . $info[0]['task_start_time'] . '</td>' .
            '			<td>' . 'N/A' . '</td>' .
            '		</tr>' .
            '	</table>'.
            '</body>' .
            '<p style="margin-top: 15px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="http://172.30.52.43/ats/index/index/' .
            'TaskManager">Link To ATS</a></p>' .
            '</html>';

        return MailerUtil::send($emailTo, $mailTitle, $content);
    }

    public function sendTaskResult() {


    }

    public function getUserAddress($taskId) {
        $testerRes = Db::table('ats_task_basic')->where('task_id', $taskId)->field('tester')->select();

        $emailRes = Db::table('users')->where('login', $testerRes[0]['tester'])->field('email')->select();

        return $emailRes[0]['email'];
    }

    private function getTaskStepsInfo($taskId, $steps) {
        // 排除basic中的result, status
        $subsql = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)->buildSql();
        $subQuery = Db::table('ats_task_basic')->where('task_id', $taskId)->field('result, status',true)->buildSql();
        $res = Db::table($subQuery.' a')->join([$subsql=> 'b'], 'a.task_id = b.task_id')->select();

        return $res;
    }

}