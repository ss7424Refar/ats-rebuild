<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-2
 * Time: 上午10:44
 */

//获取本周起始时间戳和结束时间戳
$beginThisweek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y'));
$endThisweek=time();

echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))),"\n";

echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))),"\n";

// 本周
echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))),"\n";

echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))),"\n";

echo '======';

echo date("w",strtotime("2018-11-26 13:18:20"));

echo '======';
echo date("t");


echo '======';

$arr[6]=9;$arr[7]=10;$arr[8]=123;$arr[9]=33;$arr[4]=173;$arr[2]=13;$arr[11]=22;
ksort($arr);
var_dump(json_encode($arr));

echo '===';

$cars=array("Volvo","BMW","Toyota");
var_dump(json_encode($cars));