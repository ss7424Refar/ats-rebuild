<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-10-23
 * Time: 下午8:27
 */

namespace app\index\controller;

use app\index\model\AtsTaskBasic;
use app\index\model\AtsTaskToolSteps;

use ext\ToolMaker;
use ext\FTPUtil;

class ToolHandler extends Common {
    /*
     * select2 plugins
     */
    public function getTestImage(){
        $query = $this->request->param('q');

        $ftpUtil = new FTPUtil(config('host_name'), config('host_user'), config('host_pass'));
        $images = $ftpUtil->get_file_list(config('ats_ftp_image'));

        $jsonResult = array();

        if ($images) {
            for ($i = 0; $i < count($images); $i++) {
                $filename = str_replace(config('ats_ftp_image'), '', $images[$i]);
                if (empty(trim($query))) {
                    $tmpArray = array('id' => $filename, 'text' => $filename);
                    $i = $i + 1;
                    array_push($jsonResult, $tmpArray);
                } else {
                    if (stristr($filename, $query) !== false) {
                        $tmpArray = array('id' => $filename, 'text' => $filename);
                        $i = $i + 1;
                        array_push($jsonResult, $tmpArray);
                    }
                }
            }

        }

        $ftpUtil->ftp_bye();

        return json_encode($jsonResult);

    }

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
}