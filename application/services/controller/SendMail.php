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
 * mail interface for ats
 */
class SendMail extends Controller {
    private $today;

    public function _initialize(){
        parent::_initialize();

        $this->today = date("Y-m-d");
    }

    /** baseline function
     * http://localhost/ats/services/SendMail/sendBaseLine?taskId=81&steps=2
     * @return mixed
     */
    public function sendBaseLine() {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');

        $emailTo = $this->getUserAddress($taskId);

        $info = $this->getTaskStepsInfo($taskId, $steps);

        $testImage = json_decode($info[0]['element_json']);
        $testImage = $testImage->Test_Image;

        $mailTitle = '[ATS][' . $this->today . ']['. $info[0]['tool_name'] .']You Need to run the baseline image';

        $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/' .
            'office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">' .
            '   <head>' .
            '       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Generator" >' .
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
            '		<tr bgcolor="##E5EECC">' .
            '			<th>Task ID</th><th>Machine ID</th><th>Test Machine</th><th>Assigned Task</th><th>Test Image</th>' .
            '			<th>Test Result</th><th>Start Date</th><th>Finish Date</th>' .
            '		</tr>' .
            '       <tr>' .
            '			<td>ATS_' . $info[0]['task_id'] . '</td>' .
            '			<td>' . $info[0]['machine_id'] . '</td>' .
            '			<td>' . $info[0]['machine_name'] . '</td>' .
            '			<td>'. JUMP_START .'</td>' .
            '			<td>'. $testImage .'</td>' .
            '			<td>' . $info[0]['status'] . '</td>' .
            '			<td>' . $info[0]['task_start_time'] . '</td>' .
            '			<td>' . 'N/A' . '</td>' .
            '		</tr>' .
            '	</table>'.
            '</body>' .
            '<p style="margin-top: 15px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="'.ATS_URL .'">Link To ATS</a></p>' .
            '</html>';

        return MailerUtil::send($emailTo, config('mail_cc_baseline'), $mailTitle, $content);
    }

    /**
     * send steps result
     * http://localhost/ats/services/SendMail/sendStepsResult?taskId=81&steps=2 (验证用)
     * @param $taskId
     * @param $steps
     * @return string
     */
    public function sendStepsResult($taskId, $steps) {

        $emailTo = $this->getUserAddress($taskId);

        $info = $this->getTaskStepsInfo($taskId, $steps);

        $mailTitle = '[ATS][' . $this->today  . ']['. $info[0]['tool_name'] .']Test result is '. $info[0]['status'];

        $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/' .
            'office/2004/12/omml" xmlns="http://www.w3.org/TR/REC-html40">' .
            '   <head>' .
            '       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="Generator" >' .
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
            '		<p>' . '[' .  $info[0]['tool_name']  . ']' . ' test result is '. $info[0]['status'] .'.</p>'.
            '		<p style="font-size:12px;color:red"><i>Test task as below.</i></p>' .
            '	<table>' .
            '		<tr bgcolor="##E5EECC">' .
            '			<th>Task ID</th><th>Steps</th><th>Machine ID</th><th>Test Machine</th><th>Assigned Task</th>' .
            '			<th>Task Status</th><th>Start Date</th><th>Finish Date</th>' .
            '		</tr>' .
            '       <tr>' .
            '			<td>ATS_' . $info[0]['task_id'] . '</td>' .
            '			<td>' . $info[0]['steps'] . '</td>' .
            '			<td>' . $info[0]['machine_id'] . '</td>' .
            '			<td>' . $info[0]['machine_name'] . '</td>' .
            '			<td>' . $info[0]['tool_name'] . '</td>' .
            '			<td>' . $info[0]['status']  . '</td>' .
            '			<td>' . $info[0]['tool_start_time'] . '</td>' .
            '			<td>' . $info[0]['tool_end_time'] . '</td>' .
            '		</tr>' .
            '	</table></body>' .
            '<p style="margin-top: 15px">Click here to view task list:&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="'.ATS_URL .'">Link To ATS</a></p>' .
            '</html>';

//            return $content;
        return MailerUtil::send($emailTo, config('mail_cc'), $mailTitle, $content);

    }

    private function getUserAddress($taskId) {
        $testerRes = Db::table('ats_task_basic')->where('task_id', $taskId)->field('tester')->select();

        $emailRes = Db::table('users')->where('login', $testerRes[0]['tester'])->field('email')->select();

        return $emailRes[0]['email'];
    }

    private function getTaskStepsInfo($taskId, $steps) {
        // 排除basic中的status
        $subsql = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)->buildSql();
        $subQuery = Db::table('ats_task_basic')->where('task_id', $taskId)->field('status',true)->buildSql();
        $res = Db::table($subQuery.' a')->join([$subsql=> 'b'], 'a.task_id = b.task_id')->select();

        return $res;
    }
}