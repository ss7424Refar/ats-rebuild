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

        $template = '<button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. JUMP_START .'</b></button>'.
                    '<div id="collapse_'. $index .'" class="panel-collapse collapse in">'.
                    '    <div class="panel-body form-horizontal">'.
                    '        <div class="form-group">'.
                    '            <label class="col-sm-1 control-label">TestImage</label>'.
                    '            <div class="col-sm-4">'.
                    '                <select class="form-control select2" name="TestImage" id="TestImage1"></select>'.
                    '            </div>'.
                    '            <label class="col-sm-1 control-label">Execute Job</label>'.
                    '            <div class="col-sm-4">'.
                    '                <select class="form-control select" name="Execute Job">'.
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

}