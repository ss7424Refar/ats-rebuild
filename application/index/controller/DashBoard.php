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

class DashBoard extends Common
{
    // machine chart
    public function machineChart()
    {
        $timer = $this->request->param('timer');

        $optionResult = array();
        if (HOUR == $timer) {
            //查询当天的数据并且以hour分组
            $resInhouse = Db::query(' SELECT HOUR(task_create_time) as hour,count(*) as total, category' .
                                ' FROM ats_task_basic WHERE date(task_create_time) = curdate() and category = ?' .
                                ' GROUP BY Hour,category ORDER BY category;', ['Inhouse']);

            $resODM = Db::query(' SELECT HOUR(task_create_time) as hour,count(*) as total, category' .
                ' FROM ats_task_basic WHERE date(task_create_time) = curdate() and category = ?' .
                ' GROUP BY Hour,category ORDER BY category;', ['ODM']);

            $inhouseSerial = ChartUtil::makeMachineOption($timer, $resInhouse);
            $ODMSerial = ChartUtil::makeMachineOption($timer, $resODM);
            if (null != $inhouseSerial) {
                $optionResult['seriesData'][] = $inhouseSerial;
                $optionResult['seriesName'][] = 'Inhouse';
            }
            if (null != $ODMSerial) {
                $optionResult['seriesData'][] = $ODMSerial;
                $optionResult['seriesName'][] = 'ODM';
            }

        } elseif (WEEK == $timer) {
            // 本周时间
            $weekStart = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));
            $weekEnd = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));

            //查询本周的数据并且以Week分组(0=星期一，1=星期二, ……6= 星期天)
            $res = Db::query(' SELECT WEEKDAY(task_start_time) Week, count(*) as total, machine_id, machine_name ' .
                                ' FROM ats_task_basic WHERE task_start_time BETWEEN ? AND ? ' .
                                ' group by week, machine_id, machine_name order BY machine_id, machine_name ;', [$weekStart, $weekEnd]);

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

            // 根据machineId填充一周星期，
            for ($i = 0; $i < count($machineIdArray); $i++) {
                // echarts series下的data数组
                $weekArray = array();
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]['machine_id'] == $machineIdArray[$i]) {
                        if (array_key_exists($res[$j]['Week'], $weekArray)) {
                            $weekArray[$res[$j]['Week']] += $res[$j]['total'];
                        } else {
                            $weekArray[$res[$j]['Week']] = $res[$j]['total'];
                        }
                    }
                }

                if (null != $weekArray) {
                    for ($k = 0; $k < 7; $k++) {
                        // 键名如果不重复则添加0
                        if (!array_key_exists($k, $weekArray)) {
                            $weekArray[$k] = 0;
                        }
                    }

                }
                // 按照键名升序排序
                ksort($weekArray);
                // 放入二维数组中
                $optionResult['seriesData'][$i] = $weekArray;


            }

            // echarts series下的Name数组
            if (null != $machineNameArray) {
                $optionResult['seriesName'] = $machineNameArray;
            }
        } elseif (DAY == $timer) {
            // 本月按天数统计的数据
            $res = Db::query(' SELECT DAYOFMONTH(task_start_time) Days, count(*) as total, machine_id, machine_name ' .
                ' FROM ats_task_basic WHERE DATE_FORMAT(task_start_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) ' .
                ' group by days, machine_id, machine_name order BY machine_id, machine_name;');

            //当月天数
            $days = date("t");
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

            // 根据machineId填充一个月的天数，
            for ($i = 0; $i < count($machineIdArray); $i++) {
                // echarts series下的data数组
                $dayArray = array();
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]['machine_id'] == $machineIdArray[$i]) {
                        if (array_key_exists($res[$j]['Days'], $dayArray)) {
                            $dayArray[$res[$j]['Days']] += $res[$j]['total'];
                        } else {
                            $dayArray[$res[$j]['Days']] = $res[$j]['total'];
                        }
                    }
                }

                if (null != $dayArray) {
                    for ($k = 1; $k <= $days; $k++) {
                        // 键名如果不重复则添加0
                        if (!array_key_exists($k, $dayArray)) {
                            $dayArray[$k] = 0;
                        }
                    }

                }
                // 升序排序
                ksort($dayArray);
//                var_dump($dayArray);
                // 放入二维数组中
                $optionResult['seriesData'][$i] = array_values($dayArray);


            }

            // echarts series下的Name数组
            if (null != $machineNameArray) {
                $optionResult['seriesName'] = $machineNameArray;
            }

        } elseif (MONTH == $timer) {
            // 本月按天数统计的数据
            $res = Db::query(' select month(task_start_time) Month, count(*) as total, machine_id, machine_name  ' .
                ' from `ats_task_basic` where YEAR(task_start_time)=YEAR(NOW())  ' .
                ' GROUP BY Month, machine_id, machine_name ORDER BY machine_id, machine_name; ');

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

            // 根据machineId填充一年的月数，
            for ($i = 0; $i < count($machineIdArray); $i++) {
                // echarts series下的data数组
                $monthArray = array();
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]['machine_id'] == $machineIdArray[$i]) {
                        if (array_key_exists($res[$j]['Month'], $monthArray)) {
                            $monthArray[$res[$j]['Month']] += $res[$j]['total'];
                        } else {
                            $monthArray[$res[$j]['Month']] = $res[$j]['total'];
                        }
                    }
                }

                if (null != $monthArray) {
                    for ($k = 1; $k <= 12; $k++) {
                        // 键名如果不重复则添加0
                        if (!array_key_exists($k, $monthArray)) {
                            $monthArray[$k] = 0;
                        }
                    }

                }
                // 升序排序
                ksort($monthArray);
//                var_dump($dayArray);
                // 放入二维数组中
                $optionResult['seriesData'][$i] = array_values($monthArray);


            }

            // echarts series下的Name数组
            if (null != $machineNameArray) {
                $optionResult['seriesName'] = $machineNameArray;
            }
        }elseif (YEAR == $timer) {
            // 按年统计的数据
            $res = Db::query('select year(task_start_time) as Years, count(*) total, machine_id, machine_name ' .
                ' from ats_task_basic group by Years, machine_id, machine_name ' .
                ' ORDER BY machine_id, machine_name;');

            $resY = Db::query('select min(year(task_start_time)) as minYear, max(year(task_start_time)) as maxYear'.
                        ' from ats_task_basic order by task_start_time; ');
            $minYear = $resY[0]['minYear'];
            $maxYear = $resY[0]['maxYear'];

            $xAxisArray = array();
            for ($i = 0; $i <= ($maxYear - $minYear + 1); $i++){
                $xAxisArray[$i] = $minYear;
                $minYear++;
            }


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

            // 根据machineId填充，
            for ($i = 0; $i < count($machineIdArray); $i++) {
                // echarts series下的data数组
                $yearArray = array();
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]['machine_id'] == $machineIdArray[$i]) {
                        if (array_key_exists($res[$j]['Years'], $yearArray)) {
                            $yearArray[$res[$j]['Years']] += $res[$j]['total'];
                        } else {
                            $yearArray[$res[$j]['Years']] = $res[$j]['total'];
                        }
                    }
                }

                if (null != $yearArray) {
                    for ($k = $resY[0]['minYear']; $k <= $resY[0]['maxYear']; $k++) {
                        // 键名如果不重复则添加0
                        if (!array_key_exists($k, $yearArray)) {
                            $yearArray[$k] = 0;
                        }
                    }

                }
                // 升序排序
                ksort($yearArray);
//                var_dump($dayArray);
                // 放入二维数组中
                $optionResult['seriesData'][$i] = array_values($yearArray);
            }

            // echarts series下的Name数组
            if (null != $machineNameArray) {
                $optionResult['seriesName'] = $machineNameArray;
                $optionResult['xAxis'] = $xAxisArray;
            }
        }

        return json_encode($optionResult);
    }
}