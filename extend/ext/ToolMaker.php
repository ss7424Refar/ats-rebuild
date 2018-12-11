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

        $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. JUMP_START .'</b></button>'.
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

        $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. RECOVERY .'</b></button>'.
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

        $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_'. $index .'">' . '<b>'. C_TEST .'</b></button>'.
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
            '                    <div class="input-group spinner col-sm-2" data-trigger="spinner">'.
            '                        <input type="text" class="form-control text-center" value="1" data-max="1000" data-min="1" data-step="1" data-rule="quantity">'.
            '                        <div class="input-group-addon">'.
            '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'.
            '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'.
            '                        </div>'.
            '                    </div>'.
            '                </div>'.
            '                <div id="Terminus" style="display: none">'.
            '                    <div class="form-inline">'.
            '                        <div class="input-group col-sm-3" style="margin-right: 5px;">'.
            '                            <select id="week">'.
            '                                <option>Monday</option>'.
            '                                <option>Tuesday</option>'.
            '                                <option>Wednesday</option>'.
            '                                <option>Thursday</option>'.
            '                                <option>Friday</option>'.
            '                                <option>Saturday</option>'.
            '                                <option>Sunday</option>'.
            '                            </select>'.
            '                        </div>'.
            '                        <div class="input-group clockpicker" data-autoclose="true">'.
            '                            <input type="text" class="form-control">'.
            '                            <span class="input-group-addon">'.
            '                                <span class="glyphicon glyphicon-time"></span>'.
            '                            </span>'.
            '                        </div>'.
            '                    </div>'.
            '                </div>'.
            '                <div id="Interval" style="display: none">'.
            '                    <div class="form-inline">'.
            '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'.
            '                            <input type="text" class="form-control text-center" value="1" data-rule="day" title="day">'.
            '                            <div class="input-group-addon">'.
            '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'.
            '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'.
            '                            </div>'.
            '                            <span class="input-group-addon bg-gray">'.
            '                                <span>Day</span>'.
            '                            </span>'.
            '                        </div> '.
            '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'.
            '                            <input type="text" class="form-control text-center" value="1" data-rule="hour" title="Hour">'.
            '                            <div class="input-group-addon">'.
            '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'.
            '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'.
            '                            </div>'.
            '                            <span class="input-group-addon bg-gray">'.
            '                                <span >Hour</span>'.
            '                            </span>'.
            '                        </div> '.
            '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'.
            '                            <input type="text" class="form-control text-center" value="1" data-rule="minute" title="Min">'.
            '                            <div class="input-group-addon">'.
            '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'.
            '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'.
            '                            </div>'.
            '                            <span class="input-group-addon bg-gray">'.
            '                                <span>Min</span>'.
            '                            </span>'.
            '                        </div> '.
            '                    </div>'.
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