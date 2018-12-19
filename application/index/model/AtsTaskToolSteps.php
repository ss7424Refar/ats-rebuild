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

    // 设置json类型字段
    protected $json = ['element_json'];
}