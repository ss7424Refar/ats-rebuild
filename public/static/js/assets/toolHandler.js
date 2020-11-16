function formToJson() {
    var obj = [];
    $('#toolForm').find('button[data-toggle="collapse"]').each(function(i) {

        _father = $(this).parent();
        toolType = $(this).children('b').html();

        if (JumpStart === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val'),
                Execute_Job: _father.find("#ExecuteJob").select2('val'),
                OS_Activation: _father.find("input[name^='OS Activation']:checked").val(), // OS Activation
                BaseLine_Image: _father.find("input[name^='BaseLine Image']:checked").val() // BaseLine Image
            };

            obj.push(item);
        } else if (Recovery === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val'),
                OS_Activation: _father.find("input[name^='OS Activation']:checked").val(),
                Count: _father.find('#count').val(),
                OOBE: _father.find("input[name^='OOBE']:checked").val(),
                WinUpdate: _father.find("input[name^='WinUpdate']:checked").val(),
                SecureBoot: _father.find("input[name^='SecureBoot']:checked").val()
            };
            obj.push(item);
        } else if (C_Test === toolType) {
            checkedVal = _father.find("input[name^='End After']:checked").val();
            if (null !== checkedVal || undefined !== checkedVal) {
                item = null;
                if ('Count' === checkedVal) {
                    item = {
                        Tool_Type: toolType,
                        End_After: 'Count',
                        Count: _father.find('#Count').find('input').val(),
                        Test_Image: _father.find("#TestImage").select2('val')
                    }

                } else if ('Terminus' === checkedVal) {
                    item = {
                        Tool_Type: toolType,
                        End_After: 'Terminus',
                        Week: _father.find('#Terminus').find('#week').select2('val'),
                        Time: _father.find('#Terminus').find('input').val(),
                        Test_Image: _father.find("#TestImage").select2('val')
                    }

                } else if ('Interval' === checkedVal) {
                    item = {
                        Tool_Type: toolType,
                        End_After: 'Interval',
                        Day: _father.find('#Interval').find('input:eq(0)').val(),
                        Hour: _father.find('#Interval').find('input:eq(1)').val(),
                        Min: _father.find('#Interval').find('input:eq(2)').val(),
                        Test_Image: _father.find("#TestImage").select2('val')
                    }
                }
                obj.push(item);
            }
        } else if (Treboot === toolType) {
            var cad = _father.find("input[name^='CheckAllDevices']:checked").val();
            cad = cad === undefined ? 'NO' : cad;

            var dump = _father.find("input[name^='ForceDump']:checked").val();
            dump = dump === undefined ? 'NO' : dump;
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val'),
                Reboot: _father.find('#reboot').val(),
                PowerOff: _father.find('#powerOff').val(),
                Suspend: _father.find('#standBy').val(),
                Hibernation: _father.find('#hibernation').val(),
                HybridShutdown: _father.find('#hybridShutdown').val(),
                MinPowerUp: _father.find('#delay').val(),
                SecDelay: _father.find('#timeOut').val(),
                Verify: cad,
                Dump: dump
            };
            obj.push(item);
        } else if (TAndD === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val'),
                TD_Image: _father.find("#tdImage").select2('val'),
                TD_Bios: _father.find("#bios").select2('val'),
                TD_Config: _father.find("#tdConfig").select2('val'),
                TD_Type: _father.find("input[name^='Type']:checked").val()

            };
            obj.push(item);
        } else if (FastBoot === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val')
            };
            obj.push(item);
        } else if (BIOSUpdate === toolType) {
            var item = {
                Tool_Type: toolType,
                BIOS1: _father.find("#BIOS1").select2('val'),
                BIOS2: _father.find("#BIOS2").select2('val'),
                Count: _father.find('#Count').find('input').val(),
                SecureBoot: _father.find("input[name^='SecureBoot']:checked").val()
            };
            obj.push(item);
        } else if (MT === toolType) {
            var item = {
                Tool_Type: toolType,
                Count: _father.find('#Count').find('input').val(),
            };
            obj.push(item);
        } else if (HCITest === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val')
            };
            obj.push(item);
        } else if (CommonTool === toolType) {
            var item = {
                Tool_Type: toolType,
                Config_List: _father.find("#ConfigList").select2('val'),
                Test_Image: _father.find("#TestImage").select2('val')
            };
            obj.push(item);
        } else if (TrebootMS === toolType) {
            var dump = _father.find("input[name^='ForceDump']:checked").val();
            dump = dump === undefined ? 'NO' : dump;
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val'),
                ConnectedStandby: _father.find("input[id^='standBy_']").val(),
                MinPowerUp: _father.find("input[id^='delay_']").val(),
                SecDelay: _father.find("input[id^='timeOut_']").val(),
                Dump: dump
            };
            obj.push(item);
        } else if (FastBootMS === toolType) {
            var item = {
                Tool_Type: toolType,
                Test_Image: _father.find("#TestImage").select2('val')
            };
            obj.push(item);
        }

    });
    return JSON.stringify(obj);
}

function validateFormData() {
    // select option required
    var isNG = false;
    var isWin = false; // 保证windowUpdate的msg只出现一次
    var msg = '';
    // select option required
    $('#content').find('select').each(function(i) {
        if (null == $(this).val() || undefined === $(this).val()) {
            var target = $(this).attr('name');
            msg = msg + target + " Can't Be Empty" + '<br>';

            isNG = true;
        }

    });
    //data Check
    $('#toolForm').find('button[data-toggle="collapse"]').each(function(i) {
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
        // windowUpdate time 16:30~23:30 check
        winUpVal = _father.find("input[name^='WinUpdate']:checked").val();
        if ('NO' !== winUpVal && 'undefined' !== typeof(winUpVal)) {
            if (!isWin) {
                // 判断时间 (返回值是 0 （午夜） 到 23 （晚上 11 点）之间的一个整数。)
                var hh = new Date().getHours();
                var mm = new Date().getMinutes();
                // console.log(hh);
                // time is 00:~ - 15:~
                if (0 <= Number(hh) && Number(hh) < 16) {
                    msg = msg + "Window Update Time is 16:30~23:30" + '<br>';
                    isNG = true;
                    isWin = true;
                    // time is < 16:30
                } else if (16 === Number(hh) && Number(mm) < 30) {
                    msg = msg + "Window Update Time is 16:30~23:30" + '<br>';
                    isNG = true;
                    isWin = true;
                    // time is > 23:30
                } else if (23 === Number(hh) && Number(mm) > 30) {
                    msg = msg + "Window Update Time is 16:30~23:30" + '<br>';
                    isNG = true;
                    isWin = true;
                }
            }
        }
    });
    if (isNG) {
        toastr.error(msg);
    }
    return isNG;

}

// initData 初始值， dataType表示获取下拉框的类别
function select2Init(obj, url, initData, dataType) {
    if ('' === initData) {
        obj.select2({
            width: "100%",
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term, type: dataType };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: false
            },
            placeholder: 'Please Select',
            allowClear: true
        });
    } else {
        obj.select2({
            width: "100%",
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term, type: dataType };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: false
            },
            placeholder: 'Please Select',
            allowClear: true
        }).html('<option value="' + initData + '">' + initData + '</option>').trigger("change");
    }

}

function addThenInit(selection, obj, remoteUrl) {
    if (JumpStart === selection) {
        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, 'Keep Current Image', 'testImage');

        });

        // Execute Job
        obj.find('.select').each(function() {
            $(this).select2();
        });
        // OS Activation & BaseLine Image
        obj.find('input[type="radio"].minimal').each(function() {
            $(this).iCheck({ radioClass: 'iradio_minimal-blue' });
        });
    } else if (Recovery === selection) {
        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });
        // OS Activation and OOBE and Update
        obj.find('input[type="radio"].minimal').each(function() {
            $(this).iCheck({ radioClass: 'iradio_minimal-blue' });
        });

        // count
        obj.find('[data-trigger="spinner"]').spinner();


    } else if (C_Test === selection) {
        // week
        obj.find('#week').select2({
            width: "100%"
        });

        // jquery spinner init
        obj.find('[data-trigger="spinner"]').spinner();

        //datetimepicker
        obj.find('.clockpicker').clockpicker({
            default: 'now'
        });

        // End After
        obj.find('input[type="radio"].minimal').each(function() {
            $(this).iCheck({ radioClass: 'iradio_minimal-blue' }).on('ifChecked', function() {
                _father = $(this).parent().parent().parent().parent();
                _father.find('.col-sm-4').children('div').css('display', 'none');
                _father.find('#' + $(this).val()).css('display', 'block');
            });
        });

        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, 'Keep Current Image', 'testImage');
        });

    } else if (Treboot === selection) {
        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });

        // jquery spinner init
        obj.find('[data-trigger="spinner"]').spinner();

        // configure
        obj.find('input[type="checkbox"].flat').each(function() {
            $(this).iCheck({ checkboxClass: 'icheckbox_flat-yellow' }).on('ifChecked', function() {
                _father = $(this).parent().parent().parent();
                _father.find('input[type="text"]').removeAttr("disabled").val(300);

            }).on('ifUnchecked', function() {
                _father = $(this).parent().parent().parent();
                _father.find('input[type="text"]').attr("disabled", "disabled").val(0);
            });
        });

    } else if (TAndD === selection) {
        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');
        });
        obj.find('select[name="tdImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'tdImage');

        });
        obj.find('select[name="bios"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'bios');

        });
        obj.find('select[name="tdConfig"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'tdConfig');
        });

        // DMI radio
        obj.find('input[type="radio"].minimal').each(function() {
            $(this).iCheck({ radioClass: 'iradio_minimal-blue' });
        });
    } else if (FastBoot === selection) {
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });
    } else if (BIOSUpdate === selection) {
        obj.find('select[name="BIOS1"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'bios1');
        });
        obj.find('select[name="BIOS2"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, 'NONE', 'bios2');
        });
        obj.find('[data-trigger="spinner"]').spinner();
        obj.find('input[type="radio"].minimal').each(function() {
            $(this).iCheck({ radioClass: 'iradio_minimal-blue' });
        });
    } else if (MT === selection) {
        obj.find('[data-trigger="spinner"]').spinner();
    } else if (HCITest === selection) {
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });
    } else if (CommonTool === selection) {
        obj.find('select[name="ConfigList"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'configList');

        });
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });
    } else if (TrebootMS === selection) {
        // testImage
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });

        // jquery spinner init
        obj.find('[data-trigger="spinner"]').spinner();

        obj.find('input[type="checkbox"].flat').each(function() {
            $(this).iCheck({ checkboxClass: 'icheckbox_flat-yellow' });
        });
    } else if (FastBootMS === selection) {
        obj.find('select[name="TestImage"]').each(function() {
            var _this = $(this);
            select2Init(_this, remoteUrl, '', 'testImage');

        });
    }
}

function getJumpStart(i, status) {

    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + JumpStart + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Execute Job</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select" name="ExecuteJob" id="ExecuteJob">' +
        '                    <option>Fast Startup,Standby,Microsoft Edge</option>' +
        '                    <option>Fast Startup</option>' +
        '                    <option>BatteryLife</option>' +
        '                    <option>Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab</option>' +
        '                    <option>Microsoft Edge</option>' +
        '                    <option>Standby</option>' +
        '                </select>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">OS Activation</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OS Activation_' + i + '" class="minimal" value="YES" ' + status + '/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OS Activation_' + i + '" class="minimal" value="NO"/> NO' +
        '                </label>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">BaseLine Image</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="BaseLine Image_' + i + '" class="minimal" value="YES" ' + status + '/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="BaseLine Image_' + i + '" class="minimal" value="NO"/> NO' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';
    return template;
}

function getRecovery(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + Recovery + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">OS Activation</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OS Activation_' + i + '" class="minimal" value="YES"/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OS Activation_' + i + '" class="minimal" value="NO" ' + status + '/> NO' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Count</label>' +
        '            <div class="col-sm-4">' +
        '                <div class="input-group spinner col-sm-2" data-trigger="spinner">' +
        '                    <input id="count" type="text" class="form-control text-center" value="1" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                    <div class="input-group-addon">' +
        '	                     <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                 <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                    </div>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Manual OOBE</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OOBE_' + i + '" class="minimal" value="YES"/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="OOBE_' + i + '" class="minimal" value="NO" ' + status + '/> NO' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Windows Update</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="WinUpdate_' + i + '" class="minimal" value="YES"/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="WinUpdate_' + i + '" class="minimal" value="NO" ' + status + '/> NO' +
        '                </label>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Enable SecureBoot</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="SecureBoot_' + i + '" class="minimal" value="YES" ' + status + '/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="SecureBoot_' + i + '" class="minimal" value="NO"/> NO' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn  addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn  delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;

}

function getCTest(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + C_Test + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">End After</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px"><input type="radio" name="End After_' + i + '" class="minimal" value="Count" ' + status + '/> Count</label>' +
        '                <label style="margin-right: 19px"><input type="radio" name="End After_' + i + '" class="minimal" value="Terminus" /> Terminus</label>' +
        '                <label style="margin-right: 19px"><input type="radio" name="End After_' + i + '" class="minimal" value="Interval" /> Interval</label>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">End After Data</label>' +
        '            <div class="col-sm-4">' +
        '                <div id="Count" style="display: block">' +
        '                    <div class="input-group spinner col-sm-2" data-trigger="spinner">' +
        '                        <input type="text" class="form-control text-center" value="1" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                        <div class="input-group-addon">' +
        '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                        </div>' +
        '                    </div>' +
        '                </div>' +
        '                <div id="Terminus" style="display: none">' +
        '                    <div class="form-inline">' +
        '                        <div class="input-group col-sm-3" style="margin-right: 5px;">' +
        '                            <select id="week">' +
        '                                <option>Monday</option>' +
        '                                <option>Tuesday</option>' +
        '                                <option>Wednesday</option>' +
        '                                <option>Thursday</option>' +
        '                                <option>Friday</option>' +
        '                                <option>Saturday</option>' +
        '                                <option>Sunday</option>' +
        '                            </select>' +
        '                        </div>' +
        '                        <div class="input-group clockpicker" data-autoclose="true" >' +
        '                            <input type="text" class="form-control" >' +
        '                            <span class="input-group-addon">' +
        '                                <span class="glyphicon glyphicon-time"></span>' +
        '                            </span>' +
        '                        </div>' +
        '                    </div>' +
        '                </div>' +
        '                <div id="Interval" style="display: none">' +
        '                    <div class="form-inline">' +
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">' +
        '                            <input type="text" class="form-control text-center" value="0" data-max="1000" data-min="0" data-step="1" data-rule="quantity" title="day">' +
        '                            <div class="input-group-addon">' +
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                            </div>' +
        '                            <span class="input-group-addon bg-gray">' +
        '                                <span>Day</span>' +
        '                            </span>' +
        '                        </div> ' +
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">' +
        '                            <input type="text" class="form-control text-center" value="0" data-max="23" data-min="0" data-step="1" data-rule="quantity" title="Hour">' +
        '                            <div class="input-group-addon">' +
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                            </div>' +
        '                            <span class="input-group-addon bg-gray">' +
        '                                <span >Hour</span>' +
        '                            </span>' +
        '                        </div> ' +
        '                        <div class="input-group spinner col-sm-3" data-trigger="spinner" style="margin-right: 10px">' +
        '                            <input type="text" class="form-control text-center" value="0" data-max="59" data-min="0" data-step="1" data-rule="quantity" title="Min">' +
        '                            <div class="input-group-addon">' +
        '                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                            </div>' +
        '                            <span class="input-group-addon bg-gray">' +
        '                                <span>Min</span>' +
        '                            </span>' +
        '                        </div> ' +
        '                    </div>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn  addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn  delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getTreboot(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + Treboot + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Reboot</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                    <input id="reboot" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                    <div class="input-group-addon">' +
        '	                     <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                 <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                    </div>' +
        '                    <label class="input-group-addon"><input type="checkbox" name="reboot_' + i + '" class="flat" ' + status + '/></label>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Power Off</label>' +
        '            <div class="col-sm-4">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="powerOff" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                     <label class="input-group-addon"><input type="checkbox" name="powerOff_' + i + '" class="flat" ' + status + '/></label>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Standby</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="standBy" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                     <label class="input-group-addon"><input type="checkbox" name="standBy_' + i + '" class="flat" ' + status + '/></label>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Hibernation</label>' +
        '            <div class="col-sm-4">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="hibernation" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                     <label class="input-group-addon"><input type="checkbox" name="Hibernation_' + i + '" class="flat" ' + status + '/></label>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Hybrid Shutdown</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner" id="spinner">' +
        '                     <input id="hybridShutdown" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                     <label class="input-group-addon"><input type="checkbox" name="hybrid_' + i + '" class="flat" ' + status + '/></label>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">delay to start up</label>' +
        '            <div class="col-sm-4">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="delay" type="text" class="form-control text-center" value="1" data-max="60" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">time out to exit</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="timeOut" type="text" class="form-control text-center" value="90" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        // 不用担心cad初始化会影响其他组件
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Check All Devices(For Verify)</label>' +
        '            <div class="col-sm-1" style="padding-top: 5px">' +
        '                 <input type="checkbox" name="CheckAllDevices_' + i + '" class="flat" value="YES" />' +
        '            </div>' +
        '            <div class="col-sm-3" style="padding-top: 5px">' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Force Dump</label>' +
        '            <div class="col-sm-1" style="padding-top: 5px">' +
        '                 <input type="checkbox" name="ForceDump_' + i + '" class="flat" value="YES" />' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn  delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;

}

function getTAndD(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + TAndD + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">TD Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="tdImage" id="tdImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Bios</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="bios" id="bios"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">TD Config</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="tdConfig" id="tdConfig"></select>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Type</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="Type_' + i + '" class="minimal" value="Auto" ' + status + '/> Auto' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="Type_' + i + '" class="minimal" value="NotAuto"/> NotAuto' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getFastBoot(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + FastBoot + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getBIOSUpdate(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + BIOSUpdate + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">BIOS1</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="BIOS1" id="BIOS1"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">BIOS2</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="BIOS2" id="BIOS2"></select>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Count</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <div id="Count">' +
        '                    <div class="input-group spinner col-sm-2" data-trigger="spinner">' +
        '                        <input type="text" class="form-control text-center" value="50" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                        <div class="input-group-addon">' +
        '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                        </div>' +
        '                    </div>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Enable SecureBoot</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="SecureBoot_' + i + '" class="minimal" value="YES" ' + status + '/> YES' +
        '                </label>' +
        '                <label style="margin-right: 19px">' +
        '                    <input type="radio" name="SecureBoot_' + i + '" class="minimal" value="NO"/> NO' +
        '                </label>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getMT(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + MT + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Count</label>' +
        '            <div class="col-sm-4" style="padding-top: 7px;padding-left: 14px">' +
        '                <div id="Count">' +
        '                    <div class="input-group spinner col-sm-2" data-trigger="spinner">' +
        '                        <input type="text" class="form-control text-center" value="1" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                        <div class="input-group-addon">' +
        '	                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                     <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                        </div>' +
        '                    </div>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getHCITest(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + HCITest + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getCommonTool(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + CommonTool + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Config List</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="ConfigList" id="ConfigList"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

function getTrebootMS(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + TrebootMS + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">Connected Standby</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="standBy_'+ i +'" type="text" class="form-control text-center" value="500" data-max="1000" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">delay to start up</label>' +
        '            <div class="col-sm-4">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="delay_'+ i +'" type="text" class="form-control text-center" value="5" data-max="60" data-min="0" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                </div>' +
        '            </div>' +
        '            <label class="col-sm-1 control-label">time out to exit</label>' +
        '            <div class="col-sm-5">' +
        '                <div class="input-group spinner col-sm-4" data-trigger="spinner">' +
        '                     <input id="timeOut_'+ i +'" type="text" class="form-control text-center" value="90" data-max="1000" data-min="1" data-step="1" data-rule="quantity">' +
        '                     <div class="input-group-addon">' +
        '	                      <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>' +
        '		                  <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>' +
        '                     </div>' +
        '                </div>' +
        '            </div>' +
        '        </div>' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Force Dump</label>' +
        '            <div class="col-sm-1" style="padding-top: 5px">' +
        '                 <input type="checkbox" name="ForceDump_' + i + '" class="flat" value="YES" />' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn  delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;

}

function getFastBootMS(i, status) {
    var template = '';

    template = '<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#collapse_' + i + '">' + '<b>' + FastBootMS + '</b></button>' +
        '<div id="collapse_' + i + '" class="panel-collapse collapse in">' +
        '    <div class="panel-body form-horizontal">' +
        '        <div class="form-group">' +
        '            <label class="col-sm-1 control-label">Test Image</label>' +
        '            <div class="col-sm-4">' +
        '                <select class="form-control select2" name="TestImage" id="TestImage"></select>' +
        '            </div>' +
        '        </div>' +
        '        <hr>' +
        '        <div class="col-md-6"><button type="button" class="btn addButton col-md-offset-10"><i class="fa fa-plus fa-fw"></i> Copy</button></div>' +
        '        <div class="col-md-6"><button type="button" class="btn delete"><i class="fa fa-remove fa-fw"></i>  delete</button></div>' +
        '    </div>' +
        '</div>';

    return template;
}

// end代表在最后添加, mid代表在中间添加
function addToolByButton(type, obj, urlLink) {
    var selection, toolId;
    selection = $('select[name="selection"]').select2('val');
    var collapseId = $('#forCollapse').val(); // 初始值为0

    if ('' === collapseId) {
        collapseId = 0;
    }
    result = '';
    if (JumpStart === selection) {
        result = getJumpStart(collapseId, 'checked');

    } else if (Recovery === selection) {
        result = getRecovery(collapseId, 'checked');

    } else if (C_Test === selection) {
        result = getCTest(collapseId, 'checked');

    } else if (Treboot === selection) {
        result = getTreboot(collapseId, 'checked');

    } else if (TAndD === selection) {
        result = getTAndD(collapseId, 'checked');

    } else if (FastBoot === selection) {
        result = getFastBoot(collapseId, null);

    } else if (BIOSUpdate === selection) {
        result = getBIOSUpdate(collapseId, 'checked');
    } else if (MT === selection) {
        result = getMT(collapseId, null);
    } else if (HCITest === selection) {
        result = getHCITest(collapseId, null);
    } else if (CommonTool === selection) {
        result = getCommonTool(collapseId, null);
    } else if (TrebootMS === selection) {
        result = getTrebootMS(collapseId, null);
    } else if (FastBootMS === selection) {
        result = getFastBootMS(collapseId, null);

    }

    if ('' !== result) {
        if ('none' === $('#content').css('display')) {
            $('#content').css('display', 'block');
        }
        if ('end' === type) {
            $('#box_body').append('<div>' + result + '</div>');
            lastDiv = $('#box_body').children('div:last');
            addThenInit(selection, lastDiv, urlLink);

        } else if ('mid' === type) {
            lastDiv = obj.parent().parent().parent().parent();
            lastDiv.after('<div>' + result + '</div>');
            addThenInit(selection, lastDiv.next(), urlLink);
        }
        $('#forCollapse').val(Number(collapseId) + 1);
    }
}