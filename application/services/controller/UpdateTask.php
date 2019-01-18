<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-19
 * Time: 下午1:46
 */

namespace app\services\controller;

use think\Controller;
use think\Db;

/*
 * interface for ats
 */
class UpdateTask extends Controller {
    private $time;
    private $statusArray;

    public function _initialize(){
        parent::_initialize();

        $this->statusArray = array(PASS, FAIL);

    }
    /* @throws
     * status = pass(0), fail(1)
     * need pass taskId & steps
     *
     * http://localhost/ats/services/UpdateTask/updater?taskId=81&steps=2&status=1
     * status = 0 为pass,
     * status = 1 为fail
     */
    public function updater() {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');
        $status = $this->request->param('status');

        // get total ats_task_tool_steps
        $total = Db::query('select count(*) as total from ats_task_tool_steps where task_id = ? ', [$taskId]);
        // get shelf_switch from  ats_task_basic
        $shelf_switch = Db::query('select shelf_switch from ats_task_basic where task_id = ? ', [$taskId]);
        $resultPath = ATS_RESULT_PATH. ATS_TMP_TASKS_HEADER. $shelf_switch[0]['shelf_switch']. ATS_FILE_UNDERLINE. $taskId;

        $total = $total[0]['total'];
        // update ats_task_tool_steps by taskId and steps
        if ($steps < $total) {
            // update finish time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps)
                ->update([
                    'status'  => $this->statusArray[$status],
                    'tool_end_time' => Db::raw('now()'), // V5.0.18+版本开始是数组中使用exp查询和更新的话，必须改成下面的方式：
                    'result_path' => $resultPath
                ]);
            // update next tool start time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps + 1)
                ->update([
                    'status'  => ONGOING,
                    'tool_start_time' => Db::raw('now()')
                ]);
        } else {
            // update finish time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps)
                ->update([
                    'status'  => $this->statusArray[$status],
                    'tool_end_time' => Db::raw('now()'), // V5.0.18+版本开始是数组中使用exp查询和更新的话，必须改成下面的方式：
                    'result_path' => $resultPath
                ]);
        }


        $process = intval($steps / $total * 100);

        $taskStatus = ONGOING;
        // ats_task_basic
        if ($steps == $total) {
            $to = Db::query('select count(*)  as total from ats_task_tool_steps where task_id = ? and status = ? ',  [$taskId, FAIL]);
            if ($to[0]['total'] > 0) {
                $taskStatus = FAIL;
            } else {
                $taskStatus = PASS;
            }
            // update finish time
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status'  => $taskStatus,
                    'process' => $process,
                    'task_end_time' => Db::raw('now()')
                ]);
        } else {
            // only update process and status
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status'  => $taskStatus,
                    'process' => $process,
                ]);
        }
        return 'done';
    }

}