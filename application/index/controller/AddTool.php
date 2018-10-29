<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 上午11:57
 */

namespace app\index\controller;

use app\index\model\AtsTaskToolSteps;
use app\index\model\AtsTool;
use ext\CreateAddToolElement;
use app\index\model\AtsToolElement;

class AddTool extends Common
{

    /*
     * get Data from Form
     * insert to DB
     */
    public function insertAddTool(){
        $taskId = $this->request->param('taskId');
        $formObj = $this->request->param('formObj');
        $formObj = json_decode($formObj); // object in array
        for ($i = 0; $i < count($formObj); $i++) {
            // 需要接口更新其他步骤的开始时间
            $startTime = ($i == 0) ? date("Y-m-d H:i:s") : null;
            // insert ats_task_tool_steps
            AtsTaskToolSteps::create([
                'task_id'  =>  $taskId,
                'tool_name' =>  $formObj[$i]->Tool_Type,
                'status' => '0',  // pending
                'steps' => $i + 1,
                'element_json' => json_encode($formObj[$i]), // trans to String
                'tool_start_time' => $startTime
            ]);

        }
        return "done";
    }

    /*
     * select2 plugins
     */
    public function getTestImage(){
        $query = $this->request->param('q');
        $handler = opendir(ATS_IMAGES_PATH);

        $i = 1;
        $jsonResult = array();

        while (($filename = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($filename != "." && $filename != "..") {
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

        return json_encode($jsonResult);

    }
    /*
     * get ToolName for select2
     */
    public function getToolName(){

        $type = $this->request->param('type');
        $query = $this->request->param('q');
        $jsonResult = array();
        $atsTool = new AtsTool();

        if ('init' == $type){
            $result = $atsTool->limit(1)->select()->toArray();
        } else {
            if (empty(trim($query))){
                $result = $atsTool->select();
            } else {
                $result = $atsTool->where('tool_name', 'like', '%$query%')->select();
            }

        }

        for($i = 0; $i < count($result); $i++){
            $jsonResult[$i]['id'] = $result[$i]['tool_id'];
            $jsonResult[$i]['text'] = $result[$i]['tool_name'];
        }

        return json_encode($jsonResult);

    }

}