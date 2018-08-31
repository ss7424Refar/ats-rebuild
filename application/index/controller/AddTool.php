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

        $atsTaskPanel = new AtsTaskPanel();

        $res = $atsTaskPanel->where('tool_name', $selection)->select();
        $resLength = count($res);

        $threshold = 1; // 阀值

        $htmlCreated = "";
        // create panelHead (single data)
        $timeStamp = time();
        $panel = $atsTaskPanel->where('tool_name', $selection)->find();
        $htmlCreated = $htmlCreated. CreateHtmlElement::panelInit($panel['tool_name'], $panel['panel_class'], $timeStamp);

        for ($i=0; $i<$resLength; $i++){
            if(1 == $threshold) {
                $htmlCreated = $htmlCreated.
                                '        <div class="form-group">';
            }

            else if(MAX_LINE_LENGTH == $threshold){
                $htmlCreated = $htmlCreated.
                                '        </div>';
                $threshold++;
            }
//            else {
//                $htmlCreated = $htmlCreated.
//                                '        <div class="form-group">';
//                $threshold = 1;
//            }

            // get Type
            if(SELECT2 == $res[$i]['html_type']){
                $htmlCreated = $htmlCreated . CreateHtmlElement::select2Init($res[$i], $threshold);
                $threshold ++;


            }

//            else if(SELECT == $res[$i]['html_type']){
//                $htmlCreated = $htmlCreated . CreateHtmlElement::selectInit($res[$i], $threshold);
//            } else if(RADIO == $res[$i]['html_type']){
//
////                $htmlCreated = $htmlCreated . CreateHtmlElement::select2Init($res[$i], $threshold);
//            }


        }


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