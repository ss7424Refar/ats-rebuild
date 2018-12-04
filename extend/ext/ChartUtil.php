<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-1
 * Time: 下午8:15
 */

namespace ext;

class ChartUtil {
    // 转换到小时for echart series's data
    public static function transHourArray($arr) {
        $hourArray = $arr;
        if (null != $hourArray) {
            for ($i = 0; $i < 24; $i++) {
                if (null == $hourArray[$i]) {
                    $hourArray[$i] = 0;
                }
            }

        }
        return $hourArray;
    }

    public static function makeMachineOption($timer, $data) {
        $start = 0; $end = 0;
        if (HOUR == $timer) {
            $start = 0;
            $end = 24;
        }

        $serialTimeData = array();
        for ($i = 0; $i < count($data); $i++) {
            $key = $data[$i]['hour'];
            if (array_key_exists($key, $serialTimeData)) {
                $serialTimeData[$key] += $data[$i]['total'];
            } else {
                $serialTimeData[$key] = $data[$i]['total'];
            }
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
            ksort($serialTimeData);
            return $serialTimeData;
        }
        return $serialTimeData;
    }

}