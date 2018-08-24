<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-20
 * Time: 上午11:42
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use app\index\model\AtsTesttaskInfo;

class TaskInfo
{
    public function task(){
        // 获得url参数
//        return input('id');

        //获得请求参数
        return Request::instance()->get('id');

    }

    //select
    public function getTaskInfo(){
        $info = Db::name('ats_testtask_info')->select();

        echo '<pre>';
        var_dump($info);
        echo '</pre>';
    }

    // use model
    public function getTaskInfoByModel(){
        $ats = new AtsTesttaskInfo();
        $info = $ats->select();
        echo '<pre>';
        var_dump($info[0]->getData());
        echo '</pre>';
        echo '-------------';
        echo '<pre>';
        var_dump($info[0]->getData('TestImage'));
        echo '</pre>';
    }

    public function addTaskInfo(){
        $atsInfo = new AtsTesttaskInfo();

        $atsInfo->TestImage='hello';
        $atsInfo->DMIModifyFlag=1;

        $atsInfo->save();
        // 获取自增ID
        echo $atsInfo->TaskID;

    }

    public function updateTaskInfo(){

        $atsInfo = AtsTesttaskInfo::get(8);

        echo $atsInfo->TestImage;

        $atsInfo->TestImage = 'hello world';

        $atsInfo->save();

        echo '<pre>';
        var_dump($atsInfo);
        echo  '</pre>';

    }


    public function deleteTaskInfo(){
        $atsInfo = AtsTesttaskInfo::get(8);

        $atsInfo->delete();
    }

    public function toJson(){
        $atsInfo = AtsTesttaskInfo::get(50);

        echo $atsInfo->toJson();

    }
}