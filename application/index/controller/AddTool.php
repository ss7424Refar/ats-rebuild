<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 上午11:57
 */

namespace app\index\controller;

use think\Controller;
use ext\CreateHtmlElement;
use app\index\model\AtsTaskPanel;

class AddTool extends Controller
{

    public function createHtmlElement(){

        $selection = $this->request->param('selection');// 需要继承控制器
        $collapseId = $this->request->param('collapseId');

        $atsTaskPanel = new AtsTaskPanel();

        $res = $atsTaskPanel->where('tool_name', $selection)->select();
        $resLength = count($res);

        $threshold = 0; // 阀值

        $htmlCreated = "";
        // create panelHead (single data)
        $panel = $atsTaskPanel->where('tool_name', $selection)->find();
        $htmlCreated = $htmlCreated. CreateHtmlElement::panelInit($panel['tool_name'], $panel['panel_class'], $collapseId);

        $trLength = $resLength % MAX_LINE_LENGTH == 0 ? ($resLength / MAX_LINE_LENGTH) : intval($resLength / MAX_LINE_LENGTH) + 1;

//        $tmpArr = array_slice($res, 2, MAX_LINE_LENGTH);
//        var_dump($tmpArr[0]['html_type']) ;

        $count = 0;
        for ($i = 0; $i < $trLength; $i++){
            $htmlCreated = $htmlCreated. ' <div class="form-group">';
            $tmpArr = array_slice($res, $i*MAX_LINE_LENGTH, MAX_LINE_LENGTH);
//            var_dump(count($tmpArr));
            for($j = 0; $j < count($tmpArr); $j++){

                // get Type
                if(SELECT2 == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateHtmlElement::select2Init($tmpArr[$j]);
                    $count++;
                }
                else if(SELECT == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateHtmlElement::selectInit($tmpArr[$j]);
                    $count++;
                }
                else if(RADIO == $tmpArr[$j]['html_type']){
                    $htmlCreated = $htmlCreated . CreateHtmlElement::radioInit($tmpArr[$j], $collapseId);
                    $count++;
                }
                if (MAX_LINE_LENGTH == $count){
                    $count = 0;
                    break;
                }
            }

            $htmlCreated = $htmlCreated. ' </div>';
        }

//        for ($i=0; $i<$resLength; $i++){
//            if(0 == $threshold) {
//                $htmlCreated = $htmlCreated.
//                                '        <div class="form-group">';
//            }
//            if(MAX_LINE_LENGTH < $threshold){
//                $htmlCreated = $htmlCreated.
//                    '        <div class="form-group">';
//                $threshold = 0;
//            }
//
//
//            if(MAX_LINE_LENGTH == $threshold){
//                $htmlCreated = $htmlCreated.
//                    '        </div>';
//                $threshold ++;
//            }
//
//        }
        // panel footer
        $htmlCreated = CreateHtmlElement::panelFooter($htmlCreated);
        return $htmlCreated;

    }

    public function getTestImage(){
        $query = $this->request->param('q');;
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


}