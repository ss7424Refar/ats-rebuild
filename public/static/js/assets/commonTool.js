// 工具名字
const JumpStart = 'JumpStart';
const Recovery = 'Recovery';
const C_Test = 'C-Test';

function formToJson() {
    var obj = [];
    $('#toolForm').find('button[data-toggle="collapse"]').each(function (i) {

        _father = $(this).parent();
        toolType = $(this).children('b').html();

        if (JumpStart === toolType) {
            var item ={
                Tool_Type:toolType,
                Test_Image:_father.find("#TestImage").select2('val'),
                Execute_Job:_father.find("#ExecuteJob").select2('val'),
                OS_Activation:_father.find("input[name^='OS Activation']:checked").val() // OS Activation first
            }

            obj.push(item);
        } else if (Recovery === toolType) {
            var item ={
                Tool_Type:toolType,
                Test_Image:_father.find("#TestImage").select2('val'),
                OS_Activation:_father.find("input[name^='OS Activation']:checked").val()
            }
            obj.push(item);
        }else if (C_Test === toolType) {
            checkedVal = _father.find("input[name^='End After']:checked").val();
            if (null !== checkedVal || undefined !== checkedVal) {
                item = null;
                if ('Count' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Count',
                        Count:_father.find('#Count').find('input').val()
                    }

                } else if ('Terminus' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Terminus',
                        Week:_father.find('#Terminus').find('#week').select2('val'),
                        Time:_father.find('#Terminus').find('input').val(),
                    }

                } else if ('Interval' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Interval',
                        Day:_father.find('#Interval').find('input:eq(0)').val(),
                        Hour:_father.find('#Interval').find('input:eq(1)').val(),
                        Min:_father.find('#Interval').find('input:eq(2)').val(),
                    }
                }
                obj.push(item);
            }
        }

    });
    return JSON.stringify(obj);
}

function validateFormData() {
    // select option required
    var isNG = false;
    var msg = '';
    // select option required
    $('#content').find('select').each(function (i) {
        if (null == $(this).val() || undefined === $(this).val()){
            var target = $(this).attr('name');
            msg = msg + target + " Can't Be Empty" + '<br>';

            isNG = true;
        }

    });
    //End After Check
    $('#toolForm').find('button[data-toggle="collapse"]').each(function (i) {
        _father = $(this).parent();
        checkedVal = _father.find("input[name^='End After']:checked").val();
        if (null !== checkedVal || undefined !== checkedVal) {
            item = null;
            if ('Terminus' === checkedVal) {
                time = _father.find('#Terminus').find('input').val();

                if ('' === time || null === time || undefined === time) {
                    msg = msg + "End After Can't Be Empty" + '<br>';
                    isNG = true;
                }
            }
        }

    });
    if (isNG) {
        toastr.error(msg);
    }
    return isNG;

}

function setJumpStart(i) {
    $template = '';

    $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>JumpStart</b></button>' +
        '<div id="collapse_' + i +'" class="panel-collapse collapse in">'+
        '    <div class="panel-body form-horizontal">'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Test Image</label>'+
        '            <div class="col-sm-4">'+
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">Execute Job</label>'+
        '            <div class="col-sm-4">'+
        '                <select class="form-control select" name="ExecuteJob" id="ExecuteJob">'+
        '                    <option>Fast Startup,Standby,Microsoft Edge</option>'+
        '                    <option>Fast Startup</option>'+
        '                    <option>BatteryLife</option>'+
        '                    <option>Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab</option>'+
        '                </select>'+
        '            </div>'+
        '        </div>'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">OS Activation</label>'+
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="OS Activation_'+ i +'" class="minimal" value="YES"/> YES'+
        '                </label>'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="OS Activation_'+ i +'" class="minimal" value="NO"/> NO'+
        '                </label>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'+
        '    </div>'+
        '</div>';
    return $template;
}

function setRecovery(i) {
    $template = '';

    $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>Recovery</b></button>' +
        '<div id="collapse_' + i +'" class="panel-collapse collapse in">'+
        '    <div class="panel-body form-horizontal">'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Test Image</label>'+
        '            <div class="col-sm-4">'+
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">OS Activation</label>'+
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="OS Activation_'+ i +'" class="minimal" value="YES"/> YES'+
        '                </label>'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="OS Activation_'+ i +'" class="minimal" value="NO"/> NO'+
        '                </label>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'+
        '    </div>'+
        '</div>';

    return $template;

}

function setCTest(i) {
    $template = '';

    $template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>C-Test</b></button>' +
        '<div id="collapse_' + i +'" class="panel-collapse collapse in">'+
        '    <div class="panel-body form-horizontal">'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">End After</label>'+
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'+
        '                <label style="margin-right: 19px"><input type="radio" name="End After_'+ i +'" class="minimal" value="Count" checked/> Count</label>'+
        '                <label style="margin-right: 19px"><input type="radio" name="End After_'+ i +'" class="minimal" value="Terminus" /> Terminus</label>'+
        '                <label style="margin-right: 19px"><input type="radio" name="End After_'+ i +'" class="minimal" value="Interval" /> Interval</label>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">End After Data</label>'+
        '            <div class="col-sm-4">'+
        '                <div id="Count" style="display: block">'+
        '                    <div class="input-group spinner col-sm-2" data-trigger="spinner">'+
        '                        <input type="text" class="form-control text-center" value="1" data-max="1000" data-min="1" data-step="1" data-rule="quantity">'+
        '                        <div class="input-group-addon">'+
        '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                        </div>'+
        '                    </div>'+
        '                </div>'+
        '                <div id="Terminus" style="display: none">'+
        '                    <div class="form-inline">'+
        '                        <div class="input-group col-sm-3" style="margin-right: 5px;">'+
        '                            <select id="week">'+
        '                                <option>Monday</option>'+
        '                                <option>Tuesday</option>'+
        '                                <option>Wednesday</option>'+
        '                                <option>Thursday</option>'+
        '                                <option>Friday</option>'+
        '                                <option>Saturday</option>'+
        '                                <option>Sunday</option>'+
        '                            </select>'+
        '                        </div>'+
        '                        <div class="input-group clockpicker" data-autoclose="true" >'+
        '                            <input type="text" class="form-control" >'+
        '                            <span class="input-group-addon">'+
        '                                <span class="glyphicon glyphicon-time"></span>'+
        '                            </span>'+
        '                        </div>'+
        '                    </div>'+
        '                </div>'+
        '                <div id="Interval" style="display: none">'+
        '                    <div class="form-inline">'+
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'+
        '                            <input type="text" class="form-control text-center" value="1" data-rule="day" title="day">'+
        '                            <div class="input-group-addon">'+
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                            </div>'+
        '                            <span class="input-group-addon bg-gray">'+
        '                                <span>Day</span>'+
        '                            </span>'+
        '                        </div> '+
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'+
        '                            <input type="text" class="form-control text-center" value="1" data-rule="hour" title="Hour">'+
        '                            <div class="input-group-addon">'+
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                            </div>'+
        '                            <span class="input-group-addon bg-gray">'+
        '                                <span >Hour</span>'+
        '                            </span>'+
        '                        </div> '+
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'+
        '                            <input type="text" class="form-control text-center" value="1" data-rule="minute" title="Min">'+
        '                            <div class="input-group-addon">'+
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                            </div>'+
        '                            <span class="input-group-addon bg-gray">'+
        '                                <span>Min</span>'+
        '                            </span>'+
        '                        </div> '+
        '                    </div>'+
        '                </div>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"> Add</button></div>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"> delete</button></div>'+
        '    </div>'+
        '</div>';

    return $template;
}