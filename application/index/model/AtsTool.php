<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-20
 * Time: 上午10:07
 */

namespace app\index\model;

use think\Model;

class AtsTool extends Model
{
    protected $pk = 'tool_id';
    //模型的数据集返回类型
    // 转成数据集对象然后再使用toArray方法
    protected $resultSetType = 'collection';

}