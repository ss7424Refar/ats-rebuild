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
     * create html
     */
    public function createAddToolElement(){

        $selection = $this->request->param('selection');// 需要继承控制器
        $toolId = $this->request->param('toolId');
        $collapseId = $this->request->param('collapseId');

        $atsToolPanel = new AtsToolElement();

        $panel = $atsToolPanel->where('tool_id', $toolId)->order('tool_id')->select();
        $resLength = count($panel);

        $htmlCreated = "";
        // create panelHead (single data)
        $htmlCreated = $htmlCreated. CreateAddToolElement::panelInit($selection, $panel[0]['panel_class'], $collapseId);

        $trLength = $resLength % MAX_LINE_LENGTH == 0 ? ($resLength / MAX_LINE_LENGTH) : intval($resLength / MAX_LINE_LENGTH) + 1;

        $count = 0;
        for ($i = 0; $i < $trLength; $i++){
            $htmlCreated = $htmlCreated. ' <div class="form-group">';
            $tmpArr = array_slice($panel, $i*MAX_LINE_LENGTH, MAX_LINE_LENGTH);
            for($j = 0; $j < count($tmpArr); $j++){

                // get Type
                if(SELECT2 == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateAddToolElement::select2Init($tmpArr[$j], $collapseId);
                    $count++;
                }
                else if(SELECT == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateAddToolElement::selectInit($tmpArr[$j], $collapseId);
                    $count++;
                }
                else if(RADIO == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateAddToolElement::radioInit($tmpArr[$j], $collapseId);
                    $count++;
                }
                else if(CHECKBOX == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateAddToolElement::checkboxInit($tmpArr[$j], $collapseId);
                    $count++;
                }
                if (MAX_LINE_LENGTH == $count){
                    $count = 0;
                    break;
                }
            }

            $htmlCreated = $htmlCreated. ' </div>';
        }

        // panel footer
        $htmlCreated = CreateAddToolElement::panelFooter($htmlCreated);
        return $htmlCreated;

    }
    /*
     * get Data from Form
     * insert to DB
     */
    public function insertTool(){
        $formData = $this->request->param('formData');
        $knowTool = $this->request->param('knowTool');
        $taskId = $this->request->param('taskId');

        $toolNameArray = explode(',', $knowTool);
        $formDataArray = explode('&', $formData);

        // extract formData
        $formDataArray2 = array();
        for ($i = 0; $i < count($formDataArray); $i++){
            if (null == $formDataArray2) {
                $formDataArray2[0] = $formDataArray[0];
            }
            $count = count($formDataArray2);
            $isEqual = false; // $formDataArray2中的key是否与$formDataArray中的key相等
            for ($j = 0; $j < $count; $j++) {
                $key1 = explode('=', $formDataArray[$i]);
                $key2 = explode('=', $formDataArray2[$j]);

                if ($key1[0] == $key2[0]) {
                    if (0 != $i) {
                        $formDataArray2[$j] =  $formDataArray2[$j] . ',' .$key1[1];
                    }
                    $isEqual = true;
                    break;
                } else {
                    $isEqual = false;
                }
            }
            if (!$isEqual) {
                $formDataArray2[] = $formDataArray[$i];
            }
        }

        // 统计工具个数
        $steps = count($toolNameArray);

        $atsToolPanel = new AtsToolElement();
        $atsTool = new AtsTool();

        // 获取tool_id
        $toolDbResult = $atsTool->select();
        $toolId = array();
        for ($i = 0; $i < $steps; $i++){
            for ($j = 0; $j < count($toolDbResult); $j++){
                if ($toolDbResult[$j]['tool_name'] == $toolNameArray[$i]) {
                    $toolId[] = $toolDbResult[$j]['tool_id'];
                }
            }

        }
        // 获得$elementJson
        $elementJson = array();
        for($i = 0; $i < $steps; $i++){

            $template = $atsToolPanel->where('tool_id', $toolId[$i])->order('tool_id')->select();

            for ($j = 0; $j < count($template); $j++) {
                $tmp = explode('=', $formDataArray2[$j]);
                $temp['name'] = urldecode($tmp[0]);
                $temp['value'] = urldecode($tmp[1]);
                $temp['element_id'] = $template[$j]['element_id'];
                array_push($elementJson, $temp);

            }
            $formDataArray2 = array_slice($formDataArray2, count($template));

            $element_json = json_encode($elementJson);
            // insert ats_task_tool_steps
            $startTime = ($i == 0) ? date("Y-m-d H:i:s") : null; // 需要接口更新其他步骤的开始时间

            AtsTaskToolSteps::create([
                'task_id'  =>  $taskId,
                'tool_name' =>  $toolNameArray[$i],
                'status' => '0',  // pending
                'steps' => $i,
                'element_json' => $element_json,
                'tool_start_time' => $startTime
            ]);

            $elementJson = array(); // 重新定义数组

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
    public function test(){

        $book = array('value'=>'xiyouji','name'=>'sanguo','element_id'=>'shuihu','d'=>'hongloumeng');
        echo json_encode($book);
    }
}