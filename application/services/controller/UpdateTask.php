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
class UpdateTask extends Controller
{
    private $time;
    private $statusArray;

    public function _initialize()
    {
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
    public function updater()
    {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');
        $status = $this->request->param('status');

        // get total ats_task_tool_steps
        $total = Db::query('select count(*) as total from ats_task_tool_steps where task_id = ? ', [$taskId]);
        $total = $total[0]['total'];
        // update ats_task_tool_steps by taskId and steps
        if ($steps < $total) {
            // update finish time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps)
                ->update([
                    'status' => $this->statusArray[$status],
                    'tool_end_time' => Db::raw('now()') // V5.0.18+版本开始是数组中使用exp查询和更新的话，必须改成下面的方式：
//                    'result_path' => $resultPath
                ]);
            // update next tool start time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps + 1)
                ->update([
                    'status' => ONGOING,
                    'tool_start_time' => Db::raw('now()')
                ]);
        } else {
            // update finish time
            Db::table('ats_task_tool_steps')
                ->where('task_id', $taskId)
                ->where('steps', $steps)
                ->update([
                    'status' => $this->statusArray[$status],
                    'tool_end_time' => Db::raw('now()') // V5.0.18+版本开始是数组中使用exp查询和更新的话，必须改成下面的方式：
//                    'result_path' => $resultPath
                ]);
        }

        $process = intval($steps / $total * 100);

        $taskStatus = ONGOING;
        // ats_task_basic
        if ($steps == $total) {
            $to = Db::query('select count(*)  as total from ats_task_tool_steps where task_id = ? and status = ? ', [$taskId, FAIL]);
            if ($to[0]['total'] > 0) {
                $taskStatus = FAIL;
            } else {
                $taskStatus = PASS;
            }
            // update finish time
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status' => $taskStatus,
                    'process' => $process,
                    'task_end_time' => Db::raw('now()')
//                    'result_path' => $resultPath
                ]);
        } else {
            // only update process and status
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status' => $taskStatus,
                    'process' => $process,
                ]);
        }
        return 'done';
    }

    /* @throws
     * status = pass(0), fail(1)
     * need pass taskId & steps
     *
     * http://localhost/ats/services/UpdateTask/updater2?taskId=81&steps=2&status=1
     * status = 0 为pass,
     * status = 1 为fail
     * 如果为最后一次执行需要传入status, 否则会出错.
     */
    public function updater2()
    {
        $taskId = $this->request->param('taskId');
        $steps = $this->request->param('steps');
        $status = $this->request->param('status');

        // 统计total
        $total = Db::table('ats_task_tool_steps')->where('tool_name', '<>', RECOVERY)
                  ->where('task_id', $taskId)->count();

        $r1 = Db::table('ats_task_tool_steps')->where('tool_name', RECOVERY)
                ->where('task_id', $taskId)->select();

        foreach ($r1 as $item) {
            $tmp = json_decode($item['element_json']);
            $total = $total + $tmp->Count;

        }

        // 查询steps中步骤总数
        $totalSteps = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->max('steps');
        // 查询steps中是否为Recovery
        $res = Db::table('ats_task_tool_steps')->where('task_id', $taskId)
            ->where('steps', $steps)->find();

        $json = json_decode($res['element_json']);

        if ($steps < $totalSteps) {
            if (RECOVERY == $res['tool_name']) {
                // 获取mini_steps
                $currentMiniSteps = $res['mini_steps'];
                $nextMiniSteps = $currentMiniSteps + 1;

                if ($currentMiniSteps < $json->Count && $nextMiniSteps != $json->Count) {
                    Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                        ->update([
                            'mini_steps' => $nextMiniSteps,
                        ]);
                }
                // 如果是最后一次, 更新结束时间
                if ($nextMiniSteps == $json->Count) {
                    Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                        ->update([
                            'mini_steps' => $nextMiniSteps,
                            'status' => $this->statusArray[$status],
                            'tool_end_time' => Db::raw('now()')
                        ]);

                    // 更新下一个steps
                    Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps + 1)
                        ->update([
                            'status' => ONGOING,
                            'tool_start_time' => Db::raw('now()')
                        ]);
                }

            } else {
                Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                    ->update([
                        'status' => $this->statusArray[$status],
                        'tool_end_time' => Db::raw('now()')
                    ]);
                // 更新下一个steps
                Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps + 1)
                    ->update([
                        'status' => ONGOING,
                        'tool_start_time' => Db::raw('now()')
                    ]);

            }

        // 最后一个steps
        } else {
            if (RECOVERY == $res['tool_name']) {
                $currentMiniSteps = $res['mini_steps'];
                $nextMiniSteps = $currentMiniSteps + 1;

                if ($currentMiniSteps < $json->Count && $nextMiniSteps != $json->Count) {
                    Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                        ->update([
                            'mini_steps' => $nextMiniSteps,
                        ]);
                }

                // 如果是最后一次, 更新结束时间
                if ($nextMiniSteps == $json->Count) {
                    Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                        ->update([
                            'mini_steps' => $nextMiniSteps,
                            'status' => $this->statusArray[$status],
                            'tool_end_time' => Db::raw('now()')
                        ]);
                }

            } else {
                Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('steps', $steps)
                    ->update([
                        'status' => $this->statusArray[$status],
                        'tool_end_time' => Db::raw('now()')
                    ]);

            }
        }

        // 统计进度
        $res = Db::table('ats_task_tool_steps')->where('task_id', $taskId)
            ->where('steps', '<=', $steps)->select();

        $doneProcess = 0;
        foreach ($res as $item) {
            $doneProcess = $doneProcess + $item['mini_steps'];
        }

        $process = intval($doneProcess / $total * 100);

        $taskStatus = ONGOING;
        if ($doneProcess == $total) {
            $to = Db::query('select count(*)  as total from ats_task_tool_steps where task_id = ? and status = ? ',
                [$taskId, FAIL]);
            if ($to[0]['total'] > 0) {
                $taskStatus = FAIL;
            } else {
                $taskStatus = PASS;
            }
            Db::table('ats_task_basic')->where('task_id', $taskId)
                ->update([
                    'status' => $taskStatus,
                    'process' => $process,
                    'task_end_time' => Db::raw('now()')
                ]);
        } else {
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status' => $taskStatus,
                    'process' => $process,
                ]);
        }

        return 'done';

    }

}