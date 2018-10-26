<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-10-23
 * Time: 下午8:27
 */

namespace app\index\controller;

use app\index\model\AtsTaskToolSteps;
use app\index\model\AtsTool;
use ext\ToolMaker;

class ToolHandler extends Common {
    public function getTool(){
        $selection = $this->request->param('selection');// 需要继承控制器
        $toolId = $this->request->param('toolId');
        $index = $this->request->param('collapseId');


        if (JUMP_START == $selection) {
            return ToolMaker::getJumpStart($index);

        } else if (RECOVERY == $selection) {
            return ToolMaker::getRecovery($index) ;

        }

    }

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

}