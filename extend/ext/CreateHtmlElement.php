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

    public static function panelInit($toolName, $panelClass, $collapseId){
        $panel = "";

        $panel = '<button type="button" class="'. $panelClass .'" data-toggle="collapse" data-target="#collapse_'. $collapseId .'">'.
                     '	<b>'. $toolName .'</b>'.
                     '</button>'.
                     '<div id="collapse_'. $collapseId .'" class="panel-collapse collapse in">'.
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
    public static function select2Init($arr){
        $select2Html = "";
        $url2 = url('index/AddTool/getTestImage');
        $select2Html = '            <label class="'. LABEL_CSS.'">'. $arr['html_name'] .'</label>'.
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

    public static function selectInit($arr){
        $selectHtml = "";

        $optionHtml = "";
        if (null != $arr['html_value']) {
            $optionArr = explode('_', $arr['html_value']);
            for ($i = 0; $i < count($optionArr); $i++){
                $optionHtml = $optionHtml . '<option>'. $optionArr[$i] .'</option>';

            }

        }

        $selectHtml = '           <label class="'. LABEL_CSS.'">'. $arr['html_name'] .'</label>'.
                      '           <div class="' . TOOL_DIV_SIZE. '">'.
                      '                <select class="form-control select"  name="'. $arr['html_name'] .'">'.
                                $optionHtml.
                      '                </select>'.
                      '            </div>'.
                      '            <script>'.
                      '                         $(\'.select\').select2();'.
                      '            </script>';

        return $selectHtml;


    }

    public static function radioInit($arr, $collapseId){
        $radioHtml = "";
        $optionHtml = "";
        $clas = $arr['html_class'];
        if (null != $arr['html_value']) {
            $optionArr = explode('_', $arr['html_value']);
            for ($i = 0; $i < count($optionArr); $i++){
                if ($arr['html_default'] == $optionArr[$i]) {
                    $optionHtml = $optionHtml .
                        '<label '.RADIO_LABEL . '>'.
                        '       <input type="radio" name="'. $arr['html_name'] . '_' . $collapseId .'" class="minimal" value="'. $optionArr[$i] .'" checked/> '. $optionArr[$i].
                        '</label>';
                } else {
                    $optionHtml = $optionHtml .
                        '<label '.RADIO_LABEL . '>'.
                        '       <input type="radio" name="'. $arr['html_name'] . '_' . $collapseId .'" class="minimal" value="'. $optionArr[$i] .'"/> '. $optionArr[$i].
                        '</label>';

                }


            }

        }

        $radioHtml = '            <label class="col-sm-1 control-label">'. $arr['html_name']  .'</label>'.
                     '            <div class="col-sm-4" '. RADIO_DIV .'>'.
                                        $optionHtml.
                     '            </div>'.
                     '<script>'.
                            '$(\'input[type="radio"].minimal\').iCheck({'.
                                'radioClass: ' . "'$clas'".
                            '});'.
                     '</script>';

        return $radioHtml;

    }

}