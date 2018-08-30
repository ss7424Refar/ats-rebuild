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

        $htmlCreated = "";
        for ($i=0; $i<count($resLength); $i++){
            if(SELECT2 == $res[$i]['html_type']){
                $htmlCreated = $htmlCreated . CreateHtmlElement::select2Init($res[$i]);

            }

        }


        return $htmlCreated;

    }



}