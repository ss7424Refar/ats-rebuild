<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-20
 * Time: 上午9:47
 */

namespace app\index\model;

use think\Model;

class AtsTaskToolSteps extends Model
{
    protected $pk = 'task_id';
//    //更新的时间戳字段
//    protected $autoWriteTimestamp = 'datetime';
//    // 定义时间戳字段名
//    protected $updateTime = 'tool_start_time';
//    // 关闭自动写入create_time字段
//    protected $createTime = false;
    // 设置json类型字段
    protected $json = ['element_json'];
}