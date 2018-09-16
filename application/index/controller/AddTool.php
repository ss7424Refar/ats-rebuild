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
    /*
     * create html
     */
    public function createHtmlElement(){

        $selection = $this->request->param('selection');// 需要继承控制器
        $collapseId = $this->request->param('collapseId');

        $atsTaskPanel = new AtsTaskPanel();

        $res = $atsTaskPanel->where('tool_name', $selection)->order('id')->select();
        $resLength = count($res);

        $threshold = 0; // 阀值

        $htmlCreated = "";
        // create panelHead (single data)
        $panel = $atsTaskPanel->where('tool_name', $selection)->find();
        $htmlCreated = $htmlCreated. CreateHtmlElement::panelInit($panel['tool_name'], $panel['panel_class'], $collapseId);

        $trLength = $resLength % MAX_LINE_LENGTH == 0 ? ($resLength / MAX_LINE_LENGTH) : intval($resLength / MAX_LINE_LENGTH) + 1;

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

        // panel footer
        $htmlCreated = CreateHtmlElement::panelFooter($htmlCreated);
        return $htmlCreated;

    }
    /*
     * get Data from From
     * insert to DB
     */
    public function insertTool(){
        $steps = $this->request->param('steps');
        $formData = $this->request->param('formData');
        $knowTool = $this->request->param('knowTool');

        // change to '%20' to ' ', change to '%2C' to ','
        $formData = str_replace('%2C', ',', str_replace('%20', ' ', $formData));
        $knowTool = substr($knowTool, 0, strlen($knowTool) -1);

        $toolArray = explode(',' , $knowTool);
        $formDataArray = explode('&', $formData);

        $atsTaskPanel = new AtsTaskPanel();

        $shift = 0; // 位移

        for($i=0; $i<$steps; $i++){

            $template = $atsTaskPanel->where('tool_name', $toolArray[$i])->order('id')->select();
            $tmp = array();
            for($j=$shift; $j<($shift + count($template)); $j++){

                $temp = explode('=', $formDataArray[$j]);

                $tmp[$temp[0]] = $temp[1];

            }

            $shift = count($template);
            // insert $tmp


        }


    }

    /*
     * select2 plugins
     */
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

    public function test(){

        $book = array('a'=>'xiyouji','b'=>'sanguo','c'=>'shuihu','d'=>'hongloumeng');
        echo json_encode($book);
    }
}