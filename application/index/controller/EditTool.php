<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-10-10
 * Time: 下午8:33
 */


namespace app\index\controller;

use app\index\model\AtsTaskToolSteps;
use app\index\model\AtsToolElement;
use ext\CreateAddToolElement;

class EditTool extends Common
{
    public function setTool(){
        $taskId = $this->request->param('taskId');

        $atsTaskToolSteps = new AtsTaskToolSteps();
        $atsToolElement = new AtsToolElement();

        $steps = $atsTaskToolSteps->where('task_id', $taskId)->select();
        // get $index
        $index = $steps[0]['index'];

        // make sure the save data exist in template
        $extractArray = array();
        for ($i = 0; $i < count($steps); $i++){
            $elementJson = json_decode($steps[$i]['element_json']); // 字符串所以转换为json对象
            $elementInfo = array();
            $tempInfo = array();
            for ($j = 0; $j < count($elementJson); $j++) {
                // select
                $template = $atsToolElement->where('element_id', $elementJson[$j]->element_id)->select();
                if (count($template) > 0) {
                    $tempInfo['save_name'] = $elementJson[$j]->name;
                    $tempInfo['save_value'] = $elementJson[$j]->value;
                    $tempInfo['panel_class'] = $template[0]['panel_class'];
                    $tempInfo['html_name'] = $template[0]['html_name'];
                    $tempInfo['html_type'] = $template[0]['html_type'];
                    $tempInfo['html_class'] = $template[0]['html_class'];
                    $tempInfo['html_url'] = $template[0]['html_url'];
                    $tempInfo['html_value'] = $template[0]['html_value'];

                    $elementInfo[] = $tempInfo;
                }


            }

            $extractArray[$i]['tool_name'] = $steps[$i]['tool_name'];
            $extractArray[$i]['info'] = $elementInfo;

        }
//        dump($extractArray);
        // set html
        $EditHtml = "";
        for ($i = 0; $i < count($extractArray); $i++) {
            $toolName = $extractArray[$i]['tool_name'];
            // panel header
            $EditHtml = $EditHtml. CreateAddToolElement::panelInit($toolName, $extractArray[$i]['info'][0]['panel_class'], $index);

            $resLength = count($extractArray[$i]['info']);

            $trLength = $resLength % MAX_LINE_LENGTH == 0 ? ($resLength / MAX_LINE_LENGTH) : intval($resLength / MAX_LINE_LENGTH) + 1;
            $count = 0;
            for ($j = 0; $j < $trLength; $j++) {
                $EditHtml = $EditHtml. ' <div class="form-group">';
                $tmpArr = array_slice($extractArray[$i]['info'], $j*MAX_LINE_LENGTH, MAX_LINE_LENGTH);
                for($k = 0; $k < count($tmpArr); $k++){

                    // get Type
                    if(SELECT2 == $tmpArr[$k]['html_type']){
                        $EditHtml = $EditHtml . CreateAddToolElement::setSelect2Init($tmpArr[$k], $index);
                        $count++;
                    }
                    else if(SELECT == $tmpArr[$k]['html_type']){
                        $EditHtml = $EditHtml . CreateAddToolElement::setSelectInit($tmpArr[$k], $index);
                        $count++;
                    }
                    else if(RADIO == $tmpArr[$k]['html_type']){
                        $EditHtml = $EditHtml . CreateAddToolElement::setRadioInit($tmpArr[$k], $index);
                        $count++;
                    }
                    else if(CHECKBOX == $tmpArr[$k]['html_type']){
                        $EditHtml = $EditHtml . CreateAddToolElement::setCheckboxInit($tmpArr[$k], $index);
                        $count++;
                    }
                    if (MAX_LINE_LENGTH == $count){
                        $count = 0;
                        break;
                    }
                }

                $EditHtml = $EditHtml. ' </div>';

            }

            // panel footer
            $EditHtml = CreateAddToolElement::panelFooter($EditHtml);

            $index++;
        }
        return $EditHtml;
    }

}