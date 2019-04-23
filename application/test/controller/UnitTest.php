<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-4-23
 * Time: 下午1:39
 */

namespace app\test\controller;

use think\Controller;
use think\Db;

/**
 * 测试类
 * Class UnitTest
 * @package app\test\controller
 */
class UnitTest extends Controller
{
    public function Test1() {
        $result = Db::table('ats_task_basic')->where('task_id', 79)->field('shelf_switch')->find();
        dump($result['shelf_switch']);

        $fileName = config('ats_tasks_header'). $result['shelf_switch']. config('ats_file_underline'). 79;

        $findName = $fileName. config('ats_file_suffix');
        // 31要求变成大写的Expired,所以不把这个值设入常量
        $newFileName = $fileName.config('ats_file_underline').'Expired'. config('ats_file_suffix');

        dump($findName);
        dump($newFileName);
    }
}