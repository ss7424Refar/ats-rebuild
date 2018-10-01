<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-26
 * Time: 上午11:11
 */

namespace app\index\model;
use think\Model;
use think\Session;

class AtsTaskBasic extends Model
{
    //主键
    protected $pk = 'task_id';
    //更新的时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'task_start_time';
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    // 自动完成插入属性
    protected $insert = ['status' => 0, 'tester'];
    // 自动写入tester
    protected function setTesterAttr()
    {
        return Session::get('transToAts');
    }

}