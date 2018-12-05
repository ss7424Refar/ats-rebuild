<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-1
 * Time: 下午8:15
 */

namespace ext;

class ChartUtil {
    /*
     * 生成machine_chart option
     * $args 为最大最小年份
     */
    public static function makeMachineOption($timer, $data, ...$args) {
        $start = 0; $end = 0;
        if (HOUR == $timer) {
            $start = 0;
            $end = 24;
        } elseif (WEEK == $timer) {
            $start = 0;
            $end = 7;
        } elseif (DAY == $timer) {
            $start = 1;
            $end = date("t");
        } elseif (MONTH == $timer) {
            $start = 1;
            $end = 13;
        } elseif (YEAR == $timer) {
            $start = $args[0];
            $end = $args[1] + 1;
        }

        $serialTimeData = array();
        for ($i = 0; $i < count($data); $i++) {
            $key = $data[$i][$timer];

            $serialTimeData[$key] = $data[$i]['total'];
//            if (array_key_exists($key, $serialTimeData)) {
//                $serialTimeData[$key] += $data[$i]['total'];
//            } else {
//                $serialTimeData[$key] = $data[$i]['total'];
//            }
        }

        // 填充时间，
        if (null != $serialTimeData) {
            for ($k = $start; $k < $end; $k++) {
                // 键名如果不重复则添加0
                if (!array_key_exists($k, $serialTimeData)) {
                    $serialTimeData[$k] = 0;
                }
            }
            // 按照升序排序
            if (0 == $start) {
                ksort($serialTimeData);
            } else {
                sort($serialTimeData);
            }

            return $serialTimeData;
        }
        return $serialTimeData;
    }

}