<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------


// 应用公共文件

/* 转换类似name1=value1&name2=value2的字符串为数组
 *
 */

function stringSerializeToArray($str){
    $tmpArray = explode('&', $str);

    $serializeArray = array();
    for($i = 0; $i < count($tmpArray); $i++){
        $tmp = explode('=', $tmpArray[$i]);
        $serializeArray[$tmp[0]] = urldecode($tmp[1]);
    }
    return $serializeArray;
}

/*
 * 以逗号形式返回里面的value值
 */

function appendCommaValue($arr) {
    $str = '';
    for ($i = 0; $i < count($arr); $i++) {

        $str = $str . $arr[$i]. ',';
    }
    return substr($str, 0, strlen($str) - 1);

//    return $str;
}