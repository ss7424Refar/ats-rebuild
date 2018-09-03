<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 下午1:42
 */

namespace ext;

class CreateHtmlElement
{

    public static function panelInit($toolName, $panelClass, $timeStamp){
        $panel = "";

        $panel = '<button type="button" class="'. $panelClass .'" data-toggle="collapse" data-target="#collapse_'. $timeStamp .'">'.
                     '	<b>'. $toolName .'</b>'.
                     '</button>'.
                     '<div id="collapse_'. $timeStamp .'" class="panel-collapse collapse in">'.
                     '    <div class="panel-body form-horizontal">';

        return $panel;
    }

    public static function panelFooter($htmlPanel){
        $panel = "";

        $panel = $htmlPanel.
                    '        <hr>'.
                    '        <button type="button" class="btn btn-success delete"> delete</button>'.
                    '    </div>'.
                    '</div>';

        return $panel;
    }
    public static function select2Init($arr, $threshold){
        $select2Html = "";
        $url2 = url('index/AddTool/getTestImage');
        $select2Html = '        <div class="form-group">'.
                       '            <label class="'. LABEL_CSS.'">'. $arr['html_name'] .'</label>'.
                       '           <div class="' . TOOL_DIV_SIZE. '">'.
                       '                <select class="form-control select2"  name="'. $arr['html_name'] .'"></select>'.
                       '            </div>'.
                       '<script>'.
                       '$(function(){'.
                       '	$(\'select[name="'. $arr['html_name'] .'"]\').select2({'.
                       ' 	    width: "100%",'.
                       '	    ajax: {'.
                       '		url: "'. $url2 .'",'.
                       '		dataType: \'json\','.
                       '		delay: 250,'.
                       '		data: function (params) {'.
                       '		    return {'.
                       '		        q: params.term'.
                       '		    };'.
                       '		},'.
                       '		processResults: function (data) {'.
                       '		    return {'.
                       '		        results: data'.
                       '		    };'.
                       '		},'.
                       '		cache: false'.
                       '	    },'.
                       '	    placeholder: \'Please Select\','.
                       '	    allowClear: true'.
                       '	});'.
                       '});'.
                       '</script>';
            
        return $select2Html;
    }

    public static function selectInit($arr, $threshold){



    }



}