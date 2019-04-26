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
                OS_Activation:_father.find("input[name^='OS Activation']:checked").val(), // OS Activation
                BaseLine_Image:_father.find("input[name^='BaseLine Image']:checked").val() // BaseLine Image
            };

            obj.push(item);
        } else if (Recovery === toolType) {
            var item ={
                Tool_Type:toolType,
                Test_Image:_father.find("#TestImage").select2('val'),
                OS_Activation:_father.find("input[name^='OS Activation']:checked").val()
            };
            obj.push(item);
        } else if (C_Test === toolType) {
            checkedVal = _father.find("input[name^='End After']:checked").val();
            if (null !== checkedVal || undefined !== checkedVal) {
                item = null;
                if ('Count' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Count',
                        Count:_father.find('#Count').find('input').val(),
                        Test_Image:_father.find("#TestImage").select2('val')
                    }

                } else if ('Terminus' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Terminus',
                        Week:_father.find('#Terminus').find('#week').select2('val'),
                        Time:_father.find('#Terminus').find('input').val(),
                        Test_Image:_father.find("#TestImage").select2('val')
                    }

                } else if ('Interval' === checkedVal) {
                    item ={
                        Tool_Type:toolType,
                        End_After:'Interval',
                        Day:_father.find('#Interval').find('input:eq(0)').val(),
                        Hour:_father.find('#Interval').find('input:eq(1)').val(),
                        Min:_father.find('#Interval').find('input:eq(2)').val(),
                        Test_Image:_father.find("#TestImage").select2('val')
                    }
                }
                obj.push(item);
            }
        } else if (Treboot === toolType) {
            var item ={
                Tool_Type:toolType,
                Test_Image:_father.find("#TestImage").select2('val'),
                Reboot:_father.find('#reboot').val(),
                PowerOff:_father.find('#powerOff').val(),
                Suspend:_father.find('#standBy').val(),
                Hibernation:_father.find('#hibernation').val(),
                HybridShutdown:_father.find('#hybridShutdown').val(),
                MinPowerUp:_father.find('#timeOut').val(),
                SecDelay:_father.find('#delay').val()
            };
            obj.push(item);
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
    //data Check
    $('#toolForm').find('button[data-toggle="collapse"]').each(function (i) {
        _father = $(this).parent();
        // End After Check
        checkedVal = _father.find("input[name^='End After']:checked").val();
        if (null !== checkedVal || undefined !== checkedVal) {
            if ('Terminus' === checkedVal) {
                time = _father.find('#Terminus').find('input').val();

                if ('' === time || null === time || undefined === time) {
                    msg = msg + "End After Can't Be Empty" + '<br>';
                    isNG = true;
                }
            }
        }
        // ip = 40's check
        if ('up' === $('#ip').val()) {
            // Execute Job
            executeJob = _father.find("#ExecuteJob").select2('val');
            if (job1 == executeJob || job2 == executeJob) {
                msg = msg + "Job Can't Select Battery For Ip = 40" + '<br>';
                isNG = true;
            }
        }
    });
    if (isNG) {
        toastr.error(msg);
    }
    return isNG;

}

function setJumpStart(i) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>JumpStart</b></button>' +
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
        '            <label class="col-sm-1 control-label">BaseLine Image</label>'+
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="BaseLine Image_'+ i +'" class="minimal" value="YES"/> YES'+
        '                </label>'+
        '                <label style="margin-right: 19px">'+
        '                    <input type="radio" name="BaseLine Image_'+ i +'" class="minimal" value="NO"/> NO'+
        '                </label>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Add</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>'+
        '</div>';
    return template;
}

function setRecovery(i) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>Recovery</b></button>' +
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
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Add</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>'+
        '</div>';

    return template;

}

function setCTest(i) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>C-Test</b></button>' +
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
        '                            <input type="text" class="form-control text-center" value="0" data-max="1000" data-min="0" data-step="1" data-rule="quantity" title="day">'+
        '                            <div class="input-group-addon">'+
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                            </div>'+
        '                            <span class="input-group-addon bg-gray">'+
        '                                <span>Day</span>'+
        '                            </span>'+
        '                        </div> '+
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'+
        '                            <input type="text" class="form-control text-center" value="0" data-max="23" data-min="0" data-step="1" data-rule="quantity" title="Hour">'+
        '                            <div class="input-group-addon">'+
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                            </div>'+
        '                            <span class="input-group-addon bg-gray">'+
        '                                <span >Hour</span>'+
        '                            </span>'+
        '                        </div> '+
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">'+
        '                            <input type="text" class="form-control text-center" value="0" data-max="59" data-min="0" data-step="1" data-rule="quantity" title="Min">'+
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
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Test Image</label>'+
        '            <div class="col-sm-4">'+
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Add</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>'+
        '</div>';

    return template;
}

function setTreboot(i) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i +'">' + '<b>Treboot</b></button>' +
        '<div id="collapse_' + i +'" class="panel-collapse collapse in">'+
        '    <div class="panel-body form-horizontal">'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Test Image</label>'+
        '            <div class="col-sm-4">'+
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">Reboot</label>'+
        '            <div class="col-sm-5">'+
        '                <div id="Count">'+
        '                    <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                        <input id="reboot" type="text" class="form-control text-center" value="300" data-max="1000" data-min="0" data-step="1" data-rule="quantity">'+
        '                        <div class="input-group-addon">'+
        '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                        </div>'+
        '                        <label class="input-group-addon"><input type="checkbox" name="reboot_'+ i +'" class="flat"/></label>'+
        '                    </div>'+
        '                </div>'+
        '            </div>'+
        '        </div>'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Power Off</label>'+
        '            <div class="col-sm-4">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                     <input id="powerOff" type="text" class="form-control text-center" value="300" data-max="1000" data-min="0" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                     <label class="input-group-addon"><input type="checkbox" name="powerOff_'+ i +'" class="flat"/></label>'+
        '                </div>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">Standby</label>'+
        '            <div class="col-sm-5">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                     <input id="standBy" type="text" class="form-control text-center" value="300" data-max="1000" data-min="0" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                     <label class="input-group-addon"><input type="checkbox" name="standBy_'+ i +'" class="flat"/></label>'+
        '                </div>'+
        '            </div>'+
        '        </div>'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">Hibernation</label>'+
        '            <div class="col-sm-4">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                     <input id="hibernation" type="text" class="form-control text-center" value="300" data-max="1000" data-min="0" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                     <label class="input-group-addon"><input type="checkbox" name="Hibernation_'+ i +'" class="flat"/></label>'+
        '                </div>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">Hybrid Shutdown</label>'+
        '            <div class="col-sm-5">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner" id="spinner">'+
        '                     <input id="hybridShutdown" type="text" class="form-control text-center" value="300" data-max="1000" data-min="0" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                     <label class="input-group-addon"><input type="checkbox" name="hybrid_'+ i +'" class="flat"/></label>'+
        '                </div>'+
        '            </div>'+
        '        </div>'+
        '        <div class="form-group">'+
        '            <label class="col-sm-1 control-label">delay to start up</label>'+
        '            <div class="col-sm-4">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                     <input id="delay" type="text" class="form-control text-center" value="1" data-max="60" data-min="0" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                </div>'+
        '            </div>'+
        '            <label class="col-sm-1 control-label">time out to exit</label>'+
        '            <div class="col-sm-5">'+
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">'+
        '                     <input id="timeOut" type="text" class="form-control text-center" value="90" data-max="1000" data-min="1" data-step="1" data-rule="quantity">'+
        '                     <div class="input-group-addon">'+
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>'+
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>'+
        '                     </div>'+
        '                </div>'+
        '            </div>'+
        '        </div>'+
        '        <hr>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-purple addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Add</button></div>'+
        '        <div class="col-md-6"><button type="button" class="btn bg-olive delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>'+
        '    </div>'+
        '</div>';

    return template;

}