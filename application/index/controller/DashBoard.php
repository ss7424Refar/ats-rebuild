<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-1
 * Time: 上午11:56
 */

namespace app\index\controller;

use think\Db;
use ext\ChartUtil;

/*
 * 统计后台数据，生成图标用的json
 */

class DashBoard extends Common {
    // machine chart
    public function machineChart(){
        $timer = $this->request->param('timer');

        $optionResult = array();
        if (HOUR == $timer) {
            //查询当天的数据并且以hour分组
            $res = Db::query(' SELECT HOUR(task_start_time) as Hour,count(*) as total, machine_id, machine_name'.
                                 ' FROM ats_task_basic WHERE date(task_start_time) = curdate()'.
                                 ' GROUP BY Hour,machine_id, machine_name ORDER BY machine_id, machine_name;');
            // machineId数组
            $machineIdArray = array();
            for ($i = 0; $i < count($res); $i++) {
                //存入machineId
                if (!in_array($res[$i]['machine_id'], $machineIdArray)) {
                    $machineIdArray[] = $res[$i]['machine_id'];
                }
            }
            // machineName数组
            $machineNameArray = array();
            for ($i = 0; $i < count($machineIdArray); $i++) {
                for ($j = 0; $j < count($res); $j++) {
                    if ($machineIdArray[$i] == $res[$j]['machine_id']) {
                        if (!in_array($res[$j]['machine_name'], $machineNameArray)) {
                            $machineNameArray[] = $res[$j]['machine_name'];
                            break;
                        }

                    }

                }
            }
            // 根据machineId填充一天24小时，
            for($i = 0; $i < count($machineIdArray); $i++) {
                // echarts series下的data数组
                $hourArray = array();
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]['machine_id'] == $machineIdArray[$i]) {
                        $hourArray[$res[$j]['Hour']] = $res[$j]['total'];
                    }
                }

                if (null != $hourArray) {
                    for ($k = 0; $k < 24; $k++) {
                        // 键名如果不重复则添加0
                        if (!array_key_exists($k, $hourArray)) {
                            $hourArray[$k] = 0;
                        }
                    }

                }
                // 按照键名升序排序
                ksort($hourArray);
                // 放入二维数组中
                $optionResult['seriesData'][$i] = $hourArray;


            }
            // echarts series下的Name数组
            if (null != $machineNameArray) {
                $optionResult['seriesName'] = $machineNameArray;
            }
        return json_encode($optionResult);
    }
}
}