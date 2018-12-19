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

class WatchExpired {
    /*
     * set expired
     */
    public function dog()
    {
        // select the data expired
        $result = Db::query('select * from ats_task_tool_steps  where TIMESTAMPDIFF(hour, tool_start_time, now()) >= 24 and status = ? ', [ONGOING]);

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
            }
            // update task status
            Db::table('ats_task_basic')
                ->where('task_id', $taskId)
                ->update([
                    'status' => EXPIRED
                ]);

            // send mail
            $hello = controller('services/SendMail')->getUserAddress($taskId);

            echo $hello;
        }

    }
}