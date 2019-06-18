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
    protected $createTime = 'task_create_time';
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    // 自动完成插入属性
    protected $insert = ['status' => PENDING, 'tester', 'category'];
    // 自动写入tester
    protected function setTesterAttr()
    {
        return Session::get('transToAts');
    }
    // 貌似这个修改器只是针对新增
    // 判断category // 修改器方法的第二个参数会自动传入当前的所有数据数组。
    protected function setCategoryAttr($value, $data)
    {
        // 判断是否带有Altair字符，如果有记录Inhouse字段便于统计
        if (stristr($data['machine_name'], 'Altair') !== false) {
            return 'In_House';
        }
        return 'ODM';
    }
}