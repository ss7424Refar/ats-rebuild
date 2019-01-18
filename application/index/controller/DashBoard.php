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

    private $statusArray;
    private $toolArray;
    private $weekStart;
    private $weekEnd;

    public function _initialize(){
        parent::_initialize();

        $this->statusArray = [PENDING, ONGOING, FAIL, PASS, EXPIRED];
        $this->toolArray = [JUMP_START, RECOVERY, C_TEST, TREBOOT];

        $this->weekStart = ChartUtil::getWeekStart();
        $this->weekEnd = ChartUtil::getWeekEnd();
    }

    /**myTaskInit
     * @return string
     */
    public function myTaskInit() {
        $myTaskResult = array('finished' => 0);
        for ($i = 0; $i < count($this->statusArray); $i++) {
            $res = Db::query('select count(*) as total from ats_task_basic where status = ?  '.
                ' and date(task_create_time) = curdate();', [$this->statusArray[$i]]);

            $status = $this->statusArray[$i];

            if (FAIL == $status || PASS == $status) {
                $myTaskResult[FINISHED] += $res[0]['total'];
            } else {
                $myTaskResult[$status] = $res[0]['total'];
            }
        }

        return json_encode($myTaskResult);
    }

    /** machine chart
     * @return string
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

            //查询本周的数据并且以Week分组(0=星期一，1=星期二, ……6= 星期天)
            $resIn_house = Db::query(' SELECT WEEKDAY(task_create_time) week, count(*) as total, category ' .
                                ' FROM ats_task_basic WHERE task_create_time BETWEEN ? AND ?  and category = ? ' .
                                ' group by week, category ORDER BY week;', [$this->weekStart, $this->weekEnd, 'In_House']);

            $resODM = Db::query(' SELECT WEEKDAY(task_create_time) week, count(*) as total, category ' .
                ' FROM ats_task_basic WHERE task_create_time BETWEEN ? AND ?  and category = ? ' .
                ' group by week, category ORDER BY week;', [$this->weekStart, $this->weekEnd, 'ODM']);

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
            $k = $maxYear - $minYear;
            for ($i = 0; $i <= $k; $i++){
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

    /** statusChart
     * @return string
     */
    public function statusChart() {

        $timer = $this->request->param('timer');

        $optionResult = array();

        // 判断数据是否都为0
        $isNotAllZero = false;
        for ($i = 0; $i < count($this->statusArray); $i++) {
            $serialArray = array();
            $serialArray['status'] = $this->statusArray[$i];
            if (DAY == $timer) {
                for ($j = 0; $j < count($this->toolArray); $j++) {
                    $res = Db::query('select count(*) as total from ats_task_tool_steps where status = ? and tool_name = ? '.
                        ' and date(tool_create_time) = curdate();', [$this->statusArray[$i], $this->toolArray[$j]]);

                    $serialArray['toolData'][] = (0 == $res[0]['total'] ? '-' : $res[0]['total']); // ‘—’代表数据不存在，就不再绘制了
                    if (false == $isNotAllZero) {
                        if (0 != $res[0]['total']) {
                            $isNotAllZero = true;
                        }
                    }
                }

            } elseif (WEEK == $timer) {

                for ($j = 0; $j < count($this->toolArray); $j++) {
                    $res = Db::query('select count(*) as total from ats_task_tool_steps where status = ? and tool_name = ? '.
                        ' and tool_create_time  BETWEEN ? AND ? ;', [$this->statusArray[$i], $this->toolArray[$j], $this->weekStart, $this->weekEnd]);

                    $serialArray['toolData'][] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);

                    if (false == $isNotAllZero) {
                        if (0 != $res[0]['total']) {
                            $isNotAllZero = true;
                        }
                    }
                }

            } elseif (MONTH == $timer) {
                for ($j = 0; $j < count($this->toolArray); $j++) {
                    $res = Db::query('select count(*) as total from ats_task_tool_steps where status = ? and tool_name = ? '.
                        ' AND DATE_FORMAT(tool_create_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) ;', [$this->statusArray[$i], $this->toolArray[$j]]);

                    $serialArray['toolData'][] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);

                    if (false == $isNotAllZero) {
                        if (0 != $res[0]['total']) {
                            $isNotAllZero = true;
                        }
                    }
                }

            } elseif (YEAR == $timer) {
                for ($j = 0; $j < count($this->toolArray); $j++) {
                    $res = Db::query('select count(*) as total from ats_task_tool_steps where status = ? and tool_name = ? '.
                        ' AND YEAR(tool_create_time)=YEAR(NOW());', [$this->statusArray[$i], $this->toolArray[$j]]);

                    $serialArray['toolData'][] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);

                    if (false == $isNotAllZero) {
                        if (0 != $res[0]['total']) {
                            $isNotAllZero = true;
                        }
                    }
                }
            }

            $optionResult[] = $serialArray;
        }

        if ($isNotAllZero) {
            return json_encode($optionResult);
        } else {
            return 'No Data';
        }
    }

    /** resultChart
     * @return string
     */
    public function resultChart() {
        $timer = $this->request->param('timer');

        $serialData = array();
        $optionResult = array();
        // 是否都为0
        $isNotAllZero = false;
        if (DAY == $timer) {
            for ($i = 0; $i < count($this->statusArray); $i++) {
                $res = Db::query('select count(*) as total from ats_task_basic where status = ?  '.
                    ' and date(task_create_time) = curdate();', [$this->statusArray[$i]]);

                $serialData['name'] = $this->statusArray[$i];
                // ‘—’代表数据不存在，就不再绘制了
                $serialData['value'] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);
                $optionResult[] = $serialData;

                if (false == $isNotAllZero) {
                    if (0 != $res[0]['total']) {
                        $isNotAllZero = true;
                    }
                }
            }
        } elseif (WEEK == $timer) {
            for ($i = 0; $i < count($this->statusArray); $i++) {
                $res = Db::query('select count(*) as total from ats_task_basic where status = ?  '.
                    ' and task_create_time  BETWEEN ? AND ? ;', [$this->statusArray[$i], $this->weekStart, $this->weekEnd]);

                $serialData['name'] = $this->statusArray[$i];
                // ‘—’代表数据不存在，就不再绘制了
                $serialData['value'] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);
                $optionResult[] = $serialData;

                if (false == $isNotAllZero) {
                    if (0 != $res[0]['total']) {
                        $isNotAllZero = true;
                    }
                }
            }

        } elseif (MONTH == $timer) {
            for ($i = 0; $i < count($this->statusArray); $i++) {
                $res = Db::query('select count(*) as total from ats_task_basic where status = ?  '.
                    ' AND DATE_FORMAT(task_create_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) ;', [$this->statusArray[$i]]);

                $serialData['name'] = $this->statusArray[$i];
                // ‘—’代表数据不存在，就不再绘制了
                $serialData['value'] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);
                $optionResult[] = $serialData;

                if (false == $isNotAllZero) {
                    if (0 != $res[0]['total']) {
                        $isNotAllZero = true;
                    }
                }
            }
        } elseif (YEAR == $timer) {
            for ($i = 0; $i < count($this->statusArray); $i++) {
                $res = Db::query('select count(*) as total from ats_task_basic where status = ?  '.
                    ' AND YEAR(task_create_time)=YEAR(NOW());', [$this->statusArray[$i]]);

                $serialData['name'] = $this->statusArray[$i];
                // ‘—’代表数据不存在，就不再绘制了
                $serialData['value'] = (0 == $res[0]['total'] ? '-' : $res[0]['total']);
                $optionResult[] = $serialData;

                if (false == $isNotAllZero) {
                    if (0 != $res[0]['total']) {
                        $isNotAllZero = true;
                    }
                }
            }
        }

        if ($isNotAllZero) {
            return json_encode($optionResult);
        } else {
            return 'No Data';
        }
    }

    /**
     * testerChart
     * @return string
     */
    public function testerChart() {
        $timer = $this->request->param('timer');

        $seriesData = array();
        $legendData = array();
        $selectedData = array();
        $optionResult = array();

        $res = null;

        if (DAY == $timer) {
            $res = Db::query('select tester, count(*) as total from ats_task_basic where '.
                'date(task_create_time) = curdate() group by tester;');
        } elseif (WEEK == $timer) {
            $res = Db::query('select tester, count(*) as total from ats_task_basic where '.
                'task_create_time  BETWEEN ? AND ? group by tester;', [$this->weekStart, $this->weekEnd]);
        } elseif (MONTH == $timer) {
            $res = Db::query('select tester, count(*) as total from ats_task_basic where '.
                'DATE_FORMAT(task_create_time, \'%Y%m\' ) = DATE_FORMAT(CURDATE() , \'%Y%m\' ) group by tester;');
        } elseif (YEAR == $timer) {
            $res = Db::query('select tester, count(*) as total from ats_task_basic where '.
                'YEAR(task_create_time)=YEAR(NOW()) group by tester;');
        }
        // create option
        for ($i = 0; $i < count($res); $i++) {
            $name = $res[$i]['tester'];
            $legendData[] = $name;

            $seriesData[$i]['name'] = $name;
            $seriesData[$i]['value'] = $res[$i]['total'];
            // 初始化选中个数
            if ($i < 6) {
                $selectedData[$name] = true;
            } else {
                $selectedData[$name] = false;
            }
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