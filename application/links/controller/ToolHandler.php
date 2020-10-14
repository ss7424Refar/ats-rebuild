<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-10-23
 * Time: 下午8:27
 */

namespace app\links\controller;

use app\index\model\AtsTaskBasic;
use app\index\model\AtsTaskToolSteps;

use ext\ToolMaker;

class ToolHandler extends Common {

    public function getFileName(){
        $query = $this->request->param('q');
        $type = $this->request->param('type');

        if ('testImage' == $type) {
            return $this->getSearchFile(config('ats_pe_image'), $query);
        } elseif ('tdImage' == $type) {
            return $this->getSearchFile(config('ats_td_image'), $query);
        } elseif ('bios' == $type) {
            return $this->getSearchFile(config('ats_td_bios'), $query);
        } elseif ('tdConfig' == $type) {
            return $this->getSearchFile(config('ats_td_config'), $query);
        } elseif ('bios1' == $type) {
            return $this->getSearchFile(config('ats_bios_update'), $query);
        } elseif ('bios2' == $type) {
            $res = json_decode($this->getSearchFile(config('ats_bios_update'), $query));
            // 需要增加none
            $none = array("id"=>"NONE", "text"=>"NONE");
            array_unshift($res, $none);
            return json_encode($res);
        } elseif ('toolName' == $type) {
            return $this->getToolName($query);
        } elseif ('configList' == $type) {
            return $this->getSearchFile(config('ats_config_list'), $query);
        }  elseif ('action' == $type) {
            return $this->getSearchFile(config('ats_action_list'), $query);
        }
        return '';
    }

    private function getToolName($query) {

        $jsonResult = array();

        $toolNameArray = json_decode(ToolName);
        if (empty(trim($query))) {

            foreach ($toolNameArray as $v) {
                $tmpArray = array('id' => $v, 'text' => $v);
                array_push($jsonResult, $tmpArray);
            }

        } else {
            foreach ($toolNameArray as $v) {
                if (stristr($v, $query) !== false) {
                    $tmpArray = array('id' => $v, 'text' => $v);
                    array_push($jsonResult, $tmpArray);
                }
            }
        }

        return json_encode($jsonResult);
    }

    /**
     * @return string
     * 这个接口应该是要废弃了, extend里面的toolMaker也是一样不会用到了
     */
    public function getTool(){
        $selection = $this->request->param('selection');// 需要继承控制器
        $index = $this->request->param('collapseId');

        if (JUMP_START == $selection) {
            return ToolMaker::getJumpStart($index);

        } else if (RECOVERY == $selection) {
            return ToolMaker::getRecovery($index) ;

        } else if (C_TEST == $selection) {
            return ToolMaker::getCTest($index) ;

        } else if (TREBOOT == $selection) {
            return ToolMaker::getTreboot($index) ;

        } else if (TAndD == $selection) {
            return ToolMaker::getTAndD($index) ;

        }

    }

    /* @throws
     * setTool
     */
    public function setTool(){
        $taskId = $this->request->param('taskId');

        $atsTaskToolSteps = new AtsTaskToolSteps();

        $result = $atsTaskToolSteps->where('task_id', $taskId)->order('steps')->select();

        $jsonResult = array();

        for ($i = 0; $i < count($result); $i++) {
            $jsonResult[] = json_decode($result[$i]['element_json']); // 不转换会有反斜杠生成.

        }

        return json_encode($jsonResult);

    }
    /*
     * get Data from Form
     * insert to DB
     */
    public function insertAddTool(){
        $taskId = $this->request->param('taskId');
        $formObj = $this->request->param('formObj');
        $formObj = json_decode($formObj); // object in array

        $createTime = date("Y-m-d H:i:s");

        for ($i = 0; $i < count($formObj); $i++) {
            // insert ats_task_tool_steps
            AtsTaskToolSteps::create([
                'task_id'  =>  $taskId,
                'tool_name' =>  $formObj[$i]->Tool_Type,
                'status' => PENDING,  // pending
                'steps' => $i + 1,
                'element_json' => json_encode($formObj[$i]), // trans to String
                'tool_create_time' => $createTime
            ]);

        }
        return "done";
    }

    public function updateToolSteps(){
        $taskId = $this->request->param('taskId');
        $formObj = $this->request->param('formObj');
        $formObj = json_decode($formObj); // object in array

        $createTime = date("Y-m-d H:i:s");

        if (0 != count($formObj)) {
            // delete steps
            AtsTaskToolSteps::destroy($taskId);
            for ($i = 0; $i < count($formObj); $i++) {
                // insert ats_task_tool_steps
                AtsTaskToolSteps::create([
                    'task_id'  =>  $taskId,
                    'tool_name' =>  $formObj[$i]->Tool_Type,
                    'status' => PENDING,  // pending
                    'steps' => $i + 1,
                    'element_json' => json_encode($formObj[$i]), // trans to String
                    'tool_create_time' => $createTime
                ]);

            }
            return "done";
        }
    }

    /* @throws
     *
     */
    public function deleteToolSteps(){
        $taskId = $this->request->param('taskId');

        $res = AtsTaskBasic::get(['task_id' => $taskId]);

        if (PENDING == $res->getData('status') ){
            // delete steps
            AtsTaskToolSteps::destroy($taskId);
            return 'done';
        } else {
            return 'fail';
        }

    }

    private function getSearchFile($path, $query) {
        $handler = opendir($path);

        $jsonResult = array();

        while (($filename = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($filename != "." && $filename != "..") {
                if (empty(trim($query))) {
                    $tmpArray = array('id' => $filename, 'text' => $filename);
                    array_push($jsonResult, $tmpArray);
                } else {
                    if (stristr($filename, $query) !== false) {
                        $tmpArray = array('id' => $filename, 'text' => $filename);
                        array_push($jsonResult, $tmpArray);
                    }
                }
            }
        }

        return json_encode($jsonResult);
    }

}