<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-19
 * Time: 下午5:00
 */

/**
 * timer for watch expired
 *
 */
namespace app\push\controller;

use think\Db;
use think\Log;
use think\Exception;

class WatchExpired {
    /*
     * set expired
     */
    public function dog()
    {
        // select the data expired
        $result = Db::query('select * from ats_task_tool_steps  where TIMESTAMPDIFF(hour, tool_start_time, now()) >= 72 and status = ? ', [ONGOING]);

        for ($i = 0; $i < count($result); $i++) {
            $taskId = $result[$i]['task_id'];

            Log::record('TaskId = ' . $taskId . ' ' . EXPIRED);

            // update status to expired
            $total = Db::query('select count(*) as total from ats_task_tool_steps where task_id = ? ', [$taskId]);
            $total = $total[0]['total'];
            // update for the other steps

            for ($j = $result[$i]['steps']; $j <= $total; $j++) {
                $startTime = null;
                if ($j == $result[$i]['steps']) {
                    $startTime = $result[$i]['tool_start_time'];
                }
                Db::table('ats_task_tool_steps')
                    ->where('task_id', $taskId)
                    ->where('steps', $j)
                    ->update([
                        'status' => EXPIRED,
                        'tool_start_time' => $startTime // DB设置了null， 会自动更新tool_start_time，所以只能手动加入更新接下来的step为null
                    ]);

                // send expired mail
                $sendRes = controller('services/SendMail')->sendStepsResult($taskId, $j);

                if (0 != $sendRes) {
                    Log::record('TaskId = ' . $taskId . ', ' . 'Steps = '. $j. ' Send Fail');
                }
            }
            // update task status
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status' => EXPIRED
                ]);

            // rename file name to expired
            $info = Db::table('ats_task_basic')->where('task_id', $taskId)->field('shelf_switch')->find();
            $fileName = config('ats_tasks_header'). $info['shelf_switch']. config('ats_file_underline'). $taskId;

            $findName = $fileName. config('ats_file_suffix');
            // 31要求变成大写的Expired,所以不把这个值设入常量
            $newFileName = $fileName.config('ats_file_underline').'Expired'. config('ats_file_suffix');

            Log::record('[rename] '. $findName. ' to '. $newFileName);
            try{
                exec('mv '. config('ats_pe_task'). $findName. ' '. config('ats_pe_task').$newFileName);
                Log::record('[rename][success] '. $findName. ' to '. $newFileName);

            }catch (Exception $e){
                Log::record('[rename][fail] '. $findName. ' to '. $newFileName);

            }

        }

    }
}