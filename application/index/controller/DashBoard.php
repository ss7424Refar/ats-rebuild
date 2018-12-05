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
    /*
     * machine chart
     */
    public function machineChart()
    {
        $timer = $this->request->param('timer');

        $optionResult = array();
        $resIn_house = array();
        $resODM = array();
        $xAxisArray = array(); // 年份用的横坐标

        if (HOUR == $timer) {
            //查询当天的数据并且以hour分组
            $resIn_house = Db::query(' SELECT HOUR(task_create_time) as hour,count(*) as total, category' .
                                ' FROM ats_task_basic WHERE date(task_create_time) = curdate() and category = ?' .
                                ' GROUP BY hour,category ORDER BY hour;', ['In_House']);

            $resODM = Db::query(' SELECT HOUR(task_create_time) as hour,count(*) as total, category' .
                ' FROM ats_task_basic WHERE date(task_create_time) = curdate() and category = ?' .
                ' GROUP BY hour, category ORDER BY hour;', ['ODM']);

        } elseif (WEEK == $timer) {
            //当前日期
            $today = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $first=1;
            //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $w=date('w',strtotime($today));
            //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $weekStart=date('Y-m-d',strtotime("$today -".($w ? $w - $first : 6).' days'));
            //本周结束日期
            $weekEnd=date('Y-m-d',strtotime("$weekStart + 6 days"));

            //查询本周的数据并且以Week分组(0=星期一，1=星期二, ……6= 星期天)
            $resIn_house = Db::query(' SELECT WEEKDAY(task_create_time) week, count(*) as total, category ' .
                                ' FROM ats_task_basic WHERE task_create_time BETWEEN ? AND ?  and category = ? ' .
                                ' group by week, category ORDER BY week;', [$weekStart, $weekEnd, 'In_House']);

            $resODM = Db::query(' SELECT WEEKDAY(task_create_time) week, count(*) as total, category ' .
                ' FROM ats_task_basic WHERE task_create_time BETWEEN ? AND ?  and category = ? ' .
                ' group by week, category ORDER BY week;', [$weekStart, $weekEnd, 'ODM']);

        } elseif (DAY == $timer) {
            // 本月按天数统计的数据
            $resIn_house = Db::query(' SELECT DAYOFMONTH(task_create_time) day, count(*) as total, category ' .
                ' FROM ats_task_basic WHERE category = ? AND DATE_FORMAT(task_create_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) ' .
                ' group by day, category ORDER BY day;', ['In_House']);

            $resODM = Db::query(' SELECT DAYOFMONTH(task_create_time) day, count(*) as total, category ' .
                ' FROM ats_task_basic WHERE category = ? AND DATE_FORMAT(task_create_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) ' .
                ' group by day, category ORDER BY day;', ['ODM']);

        } elseif (MONTH == $timer) {
            // 按月份统计的数据
            $resIn_house = Db::query(' select month(task_create_time) month, count(*) as total, category ' .
                ' from `ats_task_basic` WHERE category = ? AND YEAR(task_create_time)=YEAR(NOW())  ' .
                ' group by month, category ORDER BY month;', ['In_House']);

            $resODM = Db::query(' select month(task_create_time) month, count(*) as total, category ' .
                ' from `ats_task_basic` WHERE category = ? AND YEAR(task_create_time)=YEAR(NOW())  ' .
                ' group by month, category ORDER BY month;', ['ODM']);

        }elseif (YEAR == $timer) {
            // 按年统计的数据
            $resIn_house = Db::query('select year(task_create_time) as year, count(*) as total, category ' .
                ' from ats_task_basic WHERE category = ? ' .
                ' group by year, category ORDER BY year;', ['In_House']);

            $resODM = Db::query('select year(task_create_time) as year, count(*) as total, category ' .
                ' from ats_task_basic WHERE category = ? ' .
                ' group by year, category ORDER BY year;', ['ODM']);

            $resYear = Db::query('select min(year(task_create_time)) as minYear, max(year(task_create_time)) as maxYear'.
                        ' from ats_task_basic;');
            $minYear = $resYear[0]['minYear'];
            $maxYear = $resYear[0]['maxYear'];

            for ($i = 0; $i <= ($maxYear - $minYear + 1); $i++){
                $xAxisArray[$i] = $minYear;
                $minYear++;
            }

            $inhouseSerial = ChartUtil::makeMachineOption($timer, $resIn_house, $minYear, $maxYear);
            $ODMSerial = ChartUtil::makeMachineOption($timer, $resODM, $minYear, $maxYear);
            $optionResult['xAxis'] = $xAxisArray;
        }

        if (YEAR != $timer) {
            $inhouseSerial = ChartUtil::makeMachineOption($timer, $resIn_house);
            $ODMSerial = ChartUtil::makeMachineOption($timer, $resODM);
        }

        if (null != $inhouseSerial) {
            $optionResult['seriesData'][] = $inhouseSerial;
            $optionResult['seriesName'][] = 'In_House';
        }
        if (null != $ODMSerial) {
            $optionResult['seriesData'][] = $ODMSerial;
            $optionResult['seriesName'][] = 'ODM';
        }

        return json_encode($optionResult);
    }

    /*
     * resultChart
     */
    public function resultChart() {

        $timer = $this->request->param('timer');

        $statusArray = [PENDING, ONGOING, FAIL, PASS, EXPIRED];
        $toolArray = [JUMP_START, RECOVERY, C_TEST];

        $optionResult = array();

        for ($i = 0; $i < count($statusArray); $i++) {
            $serialArray = array();
            $serialArray['status'] = $statusArray[$i];
            if (DAY == $timer) {
                for ($j = 0; $j < count($toolArray); $j++) {
                    $res = Db::query('select count(*) as total from ats_task_tool_steps where status = ? and tool_name = ? '.
                        ' and date(tool_start_time) = curdate();', [$statusArray[$i], $toolArray[$j]]);

                    $serialArray['toolData'][] = $res[0]['total'];
                }

            } elseif (WEEK == $timer) {

            } elseif (MONTH == $timer) {

            } elseif (YEAR == $timer) {

            }

            $optionResult[] = $serialArray;
        }

        return json_encode($optionResult);

    }
}