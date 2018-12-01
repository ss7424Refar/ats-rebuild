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

}