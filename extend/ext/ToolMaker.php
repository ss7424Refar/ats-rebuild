<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-10-23
 * Time: 下午8:53
 */

namespace ext;

class ToolMaker {
    public static function getJumpStart($index) {
        $template = '';

        $template = '<button type="button" class="btn btn-info btn-sm btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. JUMP_START .'</b></button>'.
                    '<div id="collapse_'. $index .'" class="panel-collapse collapse in">'.
                    '    <div class="panel-body form-horizontal">'.
                    '        <div class="form-group">'.
                    '            <label class="col-sm-1 control-label">Test Image</label>'.
                    '            <div class="col-sm-4">'.
                    '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'.
                    '            </div>'.
                    '            <label class="col-sm-1 control-label">Execute Job</label>'.
                    '            <div class="col-sm-4">'.
                    '                <select class="form-control select" name="ExecuteJob" id="ExecuteJob">'.
                    '                    <option>Fast Startup,Standby,Microsoft Edge</option>'.
                    '                    <option>Fast Startup</option>'.
                    '                    <option>BatteryLife</option>'.
                    '                    <option>Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab</option>'.
                    '                </select>'.
                    '            </div>'.
                    '        </div>'.
                    '        <div class="form-group">'.
                    '            <label class="col-sm-1 control-label">OS Activation</label>'.
                    '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'.
                    '                <label style="margin-right: 19px">'.
                    '                    <input type="radio" name="OS Activation_'. $index .'" class="minimal" value="YES" checked/> YES'.
                    '                </label>'.
                    '                <label style="margin-right: 19px">'.
                    '                    <input type="radio" name="OS Activation_'. $index .'" class="minimal" value="NO"/> NO'.
                    '                </label>'.
                    '            </div>'.
                    '        </div>'.
                    '        <hr>'.
                    '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'.
                    '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'.
                    '    </div>'.
                    '</div>';
        return $template;

    }

    public static function getRecovery($index) {
        $template = '';

        $template = '<button type="button" class="btn btn-info btn-sm btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. RECOVERY .'</b></button>'.
                    '<div id="collapse_'. $index .'" class="panel-collapse collapse in">'.
                    '    <div class="panel-body form-horizontal">'.
                    '        <div class="form-group">'.
                    '            <label class="col-sm-1 control-label">Test Image</label>'.
                    '            <div class="col-sm-4">'.
                    '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'.
                    '            </div>'.
                    '            <label class="col-sm-1 control-label">OS Activation</label>'.
                    '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'.
                    '                <label style="margin-right: 19px">'.
                    '                    <input type="radio" name="OS Activation_'. $index .'" class="minimal" value="YES"/> YES'.
                    '                </label>'.
                    '                <label style="margin-right: 19px">'.
                    '                    <input type="radio" name="OS Activation_'. $index .'" class="minimal" value="NO" checked/> NO'.
                    '                </label>'.
                    '            </div>'.
                    '        </div>'.
                    '        <hr>'.
                    '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'.
                    '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'.
                    '    </div>'.
                    '</div>';

        return $template;
    }

    public static function getCTest($index){
        $template = '';

        $template = '<button type="button" class="btn btn-info btn-sm btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. C_TEST .'</b></button>'.
            '<div id="collapse_'. $index .'" class="panel-collapse collapse in">'.
            '    <div class="panel-body form-horizontal">'.
            '        <div class="form-group">'.
            '            <label class="col-sm-1 control-label">End After</label>'.
            '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'.
            '                <label style="margin-right: 19px"><input type="radio" name="End After_'. $index .'" class="minimal" value="Count" checked/> Count</label>'.
            '                <label style="margin-right: 19px"><input type="radio" name="End After_'. $index .'" class="minimal" value="Terminus" /> Terminus</label>'.
            '                <label style="margin-right: 19px"><input type="radio" name="End After_'. $index .'" class="minimal" value="Interval" /> Interval</label>'.
            '            </div>'.
            '            <label class="col-sm-1 control-label">End After Data</label>'.
            '            <div class="col-sm-4">'.
            '                <div id="Count" style="display: block">'.
            '                    <input type="number" max="1000" min="1">'.
            '                </div>'.
            '                <div id="Terminus" style="display: none">'.
            '                    <select>'.
            '                        <option>星期一</option>'.
            '                        <option>星期二</option>'.
            '                        <option>星期三</option>'.
            '                        <option>星期四</option>'.
            '                        <option>星期五</option>'.
            '                        <option>星期六</option>'.
            '                        <option>星期天</option>'.
            '                    </select>'.
            '                    <div class="input-group date">'.
            '                        <div class="input-group-addon">'.
            '                            <i class="fa fa-calendar"></i>'.
            '                        </div>'.
            '                        <input type="text" class="form-control pull-right datepicker" id="">'.
            '                    </div>'.
            '                </div>'.
            '                <div id="Interval" style="display: none">'.
            '                    <input type="number" min="0" max="365" style="margin: 5px"> Days '.
            '                    <input type="number" max="24" min="0" style="margin: 5px"> Hours '.
            '                    <input type="number" max="60" min="0" style="margin: 5px"> Mins '.
            '                </div>'.
            '            </div>'.
            '        </div>'.
            '        <hr>'.
            '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'.
            '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'.
            '    </div>'.
            '</div>';

        return $template;

    }

}