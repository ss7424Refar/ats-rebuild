$(function () {

    testMachine = $("select[name='machine_name']");
    inputDmiInfo = $('div[data-topic="inputDmiInfo"]');

    //Task
    taskButton();

    // table
    tableInit(queryParams);

    //Tool Tips 为未来元素
    $('#taskTable').on("mouseenter", "[data-toggle='tooltip']", function(){
        $(this).tooltip('show');
    }).on("mouseleave", "[data-toggle='tooltip']", function(){
        $(this).tooltip('hide');
    });

    // select2
    select2Init();

    addFormValidatorInit();

    // 当点击steps tab的时候
    $('a[href="#tab_1"]').click(function () {
        if ('block' == $('#stepsDetail').css('display')){
            $('#stepsDetail').css('display', 'none');
        }
    });
    $('a[href="#tab_2"]').click(function () {
        var taskId = $("#tab_1").find('p:eq(0)').html()
        $('#stepsTable').bootstrapTable({
            url: "{:url('TaskManager/stepsPagination')}?taskId=" + taskId,    //请求后台的URL（*）
            method: 'post',                      //请求方式（*）
            classes: 'table table-hover', // table 样式
            iconSize: 'sm',
            buttonsClass: 'success',
            striped: true,                      //是否显示行间隔色
            cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
            pagination: true,                   //是否显示分页（*）
            sortable: true,                     //是否启用排序
            sortOrder: "asc",                   //排序方式
            queryParamsType : "",                   //默认是limit，则para为params.limit, params.offset
            queryParams: function (params) {
                var temp = {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                    pageSize : params.pageSize,
                    pageNumber : params.pageNumber
                };
                return temp;//传递参数（*
            },
            clickToSelect: false,               //点击行即可选中单选/复选框
            sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
            pageNumber:1,                       //初始化加载第一页，默认第一页
            pageSize: 10,                       //每页的记录行数（*）
            pageList: [10, 25, 50],        //可供选择的每页的行数（*）
            search: false,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端
            strictSearch: true,
            showColumns: false,                  //是否显示所有的列
            showRefresh: false,                  //是否显示刷新按钮
            uniqueId: "task_id",                     //每一行的唯一标识，一般为主键列
            showToggle:false,                    //是否显示详细视图和列表视图的切换按钮
            cardView: false,                    //是否显示详细视图
            detailView: false,                   //是否显示父子表
            columns: [{
                field: 'steps',
                title: 'Steps',
                align:'center'
            }, {
                field: 'tool_name',
                title: 'Tool Name'
            },
                {
                    field: 'status',
                    title: 'Status',
                    formatter:function(value, row, index){
                        if (0==value){
                            return "pending";
                        }else if (1==value){
                            return "ongoing";
                        }else if (2==value){
                            return "finished";
                        }else if (3==value){
                            return "Cancelled";
                        }else if (4==value){
                            return "Abnormal End";
                        }else if (5==value){
                            return "expired";
                        }
                        return "N/A";
                    }
                },
                {
                    field: 'tool_start_time',
                    title: 'Start Time',
                },
                {
                    field: 'tool_end_time',
                    title: 'Finish Time'
                },
                {
                    field: 'result',
                    title: 'Test Result',
                },
                {
                    field: 'result_path',
                    title: 'Result Path'
                    // formatter: function(value, row, index){
                    //     var path = "\\\\172.30.52.28\\JSDataBK\\#Temp\\\@ATS_Results\\Task_"+ row['ShelfID'] + "_" + row.SwitchId + "_" + row.TaskID;
                    //     if("Fail"==value){
                    //         return '<a href='+ path +'><i class="fa fa-times fa-fw"></i>&nbsp;' + value + '</a>';
                    //     } else if("Pass"==value){
                    //         return '<a href='+ path +'><i class="fa fa-check fa-fw"></i>&nbsp;' + value + '</a>';
                    //     }
                    //     return "N/A";
                    // }
                },
            ],
            rowStyle: function(row, index){
                if("Fail"==row.result){
                    return {classes: 'danger'};
                }else if("Pass"==row.result){
                    return {classes: 'success'};
                }else if(0==row.status){
                    return {classes: 'info'};
                }else if(1==row.status){
                    return {classes: 'warning'};
                }
                return {classes: 'active'};
            },

            formatLoadingMessage: function () {
                return '<i class="fa fa-fw fa-spinner fa-pulse fa-2x" style="color:#96B97D"></i>';
            },

            onLoadError: function () {
                toastr.error("Table LoadError!");
            },
            formatNoMatches: function () {  //没有匹配的结果
                return '<i class="text-danger">No matching records found</i>';
            }
        }).on('click-row.bs.table', function(row, $element, field){
            $('#stepsDetail').addClass('well');

            var item = JSON.parse($element['element_json']);

            if (null !== item || undefined !== item) {
                var result = '';
                result += '<form class="form-horizontal ">';
                result += '<div class="form-group">';
                result += '    <label class="col-sm-3 control-label">Step</label>';
                result += '    <div class="col-sm-8">';
                result += '        <p class="form-control-static">' + $element['steps'] + '</p>';
                result += '    </div>';
                result += '</div>';
                for(var key in item){
                    if ('Tool_Type' !== key) {
                        result += '<div class="form-group">';
                        result += '    <label class="col-sm-3 control-label">'+ key +'</label>';
                        result += '    <div class="col-sm-8">';
                        result += '        <p class="form-control-static">' + item[key] + '</p>';
                        result += '    </div>';
                        result += '</div>';
                    }
                }
                result += '</form>';
                $('#stepsDetail').html(result);
                if ('none' === $('#stepsDetail').css('display')){
                    $('#stepsDetail').css('display', 'block');
                }
            }
        });

    });

    // assign
    assignButtonInit();

    // delete
    deleteTaskButtonInit();

});

// 决定工具的操作
function checkTool(taskId, toolOp) {
    if ('add' === toolOp) {
        window.location.href='{:url("ToolAdd")}?taskId='+ taskId ;
    } else if ('edit' === toolOp){
        window.location.href='{:url("ToolEdit")}?taskId='+ taskId ;
    } else if ('delete' === toolOp){

        bootbox.confirm({
            message: " Do You Want Delete Tool Steps?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "{:url('ToolHandler/deleteToolSteps')}",
                        data: {taskId: taskId},
                        success: function (result) {
                            console.log(result);
                            if ('fail' !== result) {
                                toastr.success("Delete Steps Success");
                                $('#taskTable').bootstrapTable('selectPage', 1);
                            }
                        },
                        error: function () {
                            toastr.error("Delete Steps Fail");
                            return false;
                        }

                    });
                }

            }
        });

    }
}
function taskButton(){
    var buttonGroup=$('.btn-group:eq(1)');
    var time=null;

    $('.btn-group:eq(0), .btn-group:eq(1)').mouseenter(function () {
        clearTimeout(time);
        buttonGroup.css('display', 'block');
    }).mouseleave(function () {
        time=setTimeout(function(){
            buttonGroup.css('display', 'none');
        },300);
    });
};

function tableInit(queryPs){
    $('#taskTable').bootstrapTable({
        url: "{:url('TaskManager/taskPagination')}",    //请求后台的URL（*）
        // url: "/ats_kimi/index/TaskManager/taskPagination",    //请求后台的URL（*）
        method: 'post',                      //请求方式（*）
        classes: 'table table-responsive table-hover table-no-bordered', // table 样式
        iconSize: 'sm',
        buttonsClass: 'warning',
        toolbar: '#toolbar',                //工具按钮用哪个容器
        striped: true,                      //是否显示行间隔色
        cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
        pagination: true,                   //是否显示分页（*）
        sortable: true,                     //是否启用排序
        sortOrder: "asc",                   //排序方式
        queryParamsType : "",                   //默认是limit，则para为params.limit, params.offset
        queryParams: queryPs,
        clickToSelect: false,               //点击行即可选中单选/复选框
        sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
        pageNumber:1,                       //初始化加载第一页，默认第一页
        pageSize: 10,                       //每页的记录行数（*）
        pageList: [10, 25, 50],        //可供选择的每页的行数（*）
        search: false,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端
        strictSearch: true,
        showColumns: true,                  //是否显示所有的列
        showRefresh: true,                  //是否显示刷新按钮
        minimumCountColumns: 2,             //最少允许的列数
        // height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
        uniqueId: "task_id",                     //每一行的唯一标识，一般为主键列
        showToggle:false,                    //是否显示详细视图和列表视图的切换按钮
        cardView: false,                    //是否显示详细视图
        detailView: false,                   //是否显示父子表
        columns: [{
            checkbox: true
        }, {
            field: 'task_id',
            title: 'TaskID',
            formatter:function(value, row, index){
                return 'ATS_' + value;
            }
        }, {
            field: 'machine_name',
            title: 'Test Machine',
            formatter:function(value, row, index){
                return value;
            }
        }, {
            field: 'machine_id',
            title: 'MachineID'
        }, {
            field: 'status',
            title: 'Task Status',
            formatter:function(value, row, index){
                if (0==value){
                    return "pending";
                }else if (1==value){
                    return "ongoing";
                }else if (2==value){
                    return "finished";
                }else if (3==value){
                    return "Cancelled";
                }else if (4==value){
                    return "Abnormal End";
                }else if (5==value){
                    return "expired";
                }
                return "N/A";
            }
        }, {
            field: 'task_start_time',
            title: 'Start Time',
        }, {
            field: 'task_end_time',
            title: 'Finish Time'
        },
            {
                field: 'process',
                title: 'Process',
                formatter: function (value, row, index) {

                    if (0 == value) {
                        return '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" style="min-width: 2em; width: 2%">0%</div>';
                    } else {
                        return '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" style="min-width: 2em; width: ' + value + '%">' + value + '%</div>';
                    }

                }
            },
            {
                field: 'result',
                title: 'Test Result',
                formatter: function(value, row, index){
                    var path = "\\\\172.30.52.28\\JSDataBK\\#Temp\\\@ATS_Results\\Task_"+ row['ShelfID'] + "_" + row.SwitchId + "_" + row.TaskID;
                    if("Fail"==value){
                        return '<a href='+ path +'><i class="fa fa-times fa-fw"></i>&nbsp;' + value + '</a>';
                    } else if("Pass"==value){
                        return '<a href='+ path +'><i class="fa fa-check fa-fw"></i>&nbsp;' + value + '</a>';
                    }
                    return "N/A";
                }
            }, {
                field: 'tester',
                title: 'Tester'
            }, {
                field: 'tool',
                title: 'Tool',
                formatter: function (value, row, index) {
                    var id = row['task_id'];
                    var sid = row['sid'];
                    var result="";

                    if (0 == row['status']) {
                        if (null == sid) {
                            result += "<a class='btn btn-xs bg-aqua' href='#' " + " onclick=\"return checkTool(\'" + id + "\', 'add')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title='add' style='margin: 3px'><span class='glyphicon glyphicon-plus'></span></a>";
                        } else {
                            result += "<a class='btn btn-xs bg-green' href='#' " + " onclick=\"return checkTool(\'" + id + "\', 'edit')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title='edit' style='margin: 3px'><span class='glyphicon glyphicon-pencil'></span></a>";
                            result += "<a class='btn btn-xs bg-red' href='#' " + " onclick=\"return checkTool(\'" + id + "\', 'delete')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title='delete' style='margin: 3px'><span class='glyphicon glyphicon-trash'></span></a>";
                        }
                    } else {
                        result += "<a class='btn btn-xs bg-red' href='#' " + "data-toggle=\"tooltip\" data-placement=\"bottom\" title='ban' style='margin: 3px'><span class='glyphicon glyphicon-ban-circle'></span></a>";
                    }

                    return result;
                }
            }
        ],
        rowStyle: function(row, index){
            if("Fail"==row.result){
                return {classes: 'danger'};
            }else if("Pass"==row.result){
                return {classes: 'success'};
            }else if(0==row.status){
                return {classes: 'info'};
            }else if(1==row.status){
                return {classes: 'warning'};
            }
            return {classes: 'active'};
        },

        formatLoadingMessage: function () {
            return '<i class="fa fa-fw fa-spinner fa-pulse fa-2x" style="color:#96B97D"></i>';
        },

        onLoadError: function () {
            toastr.error("Table LoadError!");
        },
        formatNoMatches: function () {  //没有匹配的结果
            return '<i class="text-danger">No matching records found</i>';
        }
    }).on('dbl-click-row.bs.table', function(row, $element, field){

        console.log($element);
        var taskId=$element.task_id;

        //init modal tab
        $('.nav-tabs li').each(function () {
            $(this).removeClass('active');
        });
        // delete steps detail
        if ($('#stepsDetail').attr('class','well')) {
            $('#stepsDetail').html('').removeClass('well');
        }

        $("a[href='#tab_1']").parent().addClass('active');
        $("div[id*='#tab_']").each(function () {
            $(this).removeClass('active');
        });
        $('#tab_1').addClass('active');

        $('#tab_2').html('<table id="stepsTable" class="" data-show-jumpto="true"></table>');
        // get info
        $.ajax({
            type: "post",
            url: "{:url('TaskManager/getTaskInfoById')}",
            data: {taskId: taskId},
            dataType: "json",
            success: function (result) {
                if(result){
                    var i = 0;
                    for(var key in result[0]){
                        $("#tab_1").find('p:eq(' + i + ')').html(result[0][key]);
                        i++;
                    }
                    $("#detailInfo").modal("toggle");
                }
            },
            error: function () {
                toastr.error("Get Info Fail");
                return false;
            }

        });

    });

};

function queryParams(params) {
    var temp = {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
        pageSize : params.pageSize,
        pageNumber : params.pageNumber
    };
    return temp;//传递参数（*
};

function select2Init() {

    testMachine.select2({
            width: "100%",
            ajax: {
                url: "{:url('TaskManager/readMachineInfo')}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            },
            placeholder: 'Please Select',
            allowClear: true
        }
    );

    testMachine.on("select2:select",function(e){

        $('#addTaskForm').data('bootstrapValidator').destroy();
        addFormValidatorInit();

        console.log(testMachine.val());
        var testMachineVal = testMachine.val();
        var machineId = testMachine.select2('data')[0].text;
        if (null != machineId) {
            machineId = machineId.replace(testMachineVal, '');
            machineId = machineId.substring(1, machineId.length - 1);
        }
        console.log(machineId);

        $.ajax({
            type: 'post',
            url: "{:url('TaskManager/readTestPCDetail')}",
            data: {machineId: machineId},
            dataType: 'json',
            success: function(result){
                inputDmiInfo.css('display', 'block');
                inputDmiInfo.find('input').each(function (i) {
                    $(this).val(result[i]);
                });

            },
            error: function () {
                toastr.error("Machine Info Read Failed");
            }
        });
    });
    testMachine.on("change", function (e) {
        inputDmiInfo.css('display', 'none').find('input').val('');
        $('#addTaskForm').data('bootstrapValidator').destroy();
        addFormValidatorInit();
    });
}

function assignButtonInit(){
    $('#assignTask').click( function () {
        var ckArr = $('#taskTable').bootstrapTable('getSelections');
        console.log(ckArr);

        if(ckArr.length == 0){
            toastr.info("Please select at least one checkbox");
            return false;

        } else if(ckArr.length >= 1 && ckArr.length<= 5){
            $.ajax({
                type: "get",
                url: "../functions/atsController.php?do=checkAtsInfoByMultiTaskId",
                data: {multiTask: ckArr},
                dataType: 'json',
                success: function (result) {
                    console.log(result.NoTaskIdFlag);
                    console.log(result.NotPendingFlag);

                    if (result.NoTaskIdFlag){
                        toastr.info("TaskID = " + result.saveNoTaskId + " didn't found! Please Refresh Table!");
                        return false;
                    }
                    if (result.NotPendingFlag) {
                        toastr.info("TaskID = " + result.saveNotPending + " not pending! cannnot assign to ATS");
                        return false;
                    }

                    // assign to ATS
                    $.ajax({
                        type: "get",
                        url: "../functions/atsController.php?do=assignAtsInfoByMultiTaskId",
                        data: {multiTask: ckArr},
                        dataType: 'json',
                        success: function (result) {
                            if("done" === result){
                                toastr.success("success assign to ATS");
                                $('#taskTable').bootstrapTable('selectPage', 1);
                            } else {
                                toastr.error(result);
                            }
                        },
                        error: function () {
                            toastr.error("fail assign to ATS");
                        }
                    });
                },
                error: function (xhr,status,error) {
                    toastr.error(xhr.status + " " + xhr.statusText);
                }
            });
        } else {
            toastr.warning("Please select not more than five checkbox");
            return false;
        }

    });
}


function deleteTaskButtonInit() {
    $('#deleteTask').click(function () {
        var ckArr = $('#taskTable').bootstrapTable('getSelections');
        console.log(ckArr);
        if(ckArr.length == 0){
            toastr.info("Please select at least one checkbox");
            return false;
        }
        if(ckArr.length >= 1 && ckArr.length<= 5){
            bootbox.confirm({
                message: " Do you want delete ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        $.ajax({
                            type: "post",
                            url: "../functions/atsController.php?do=checkAtsInfoByMultiTaskId",
                            data: {multiTask: ckArr},
                            dataType: 'json',
                            success: function (result2) {
                                console.log(result2.NoTaskIdFlag);
                                console.log(result2.NotPendingFlag);
                                if (result2.NoTaskIdFlag){
                                    toastr.info("TaskID = " + result2.saveNoTaskId + " didn't found! Please Refresh Table!");
                                    return false;
                                }
                                if (result2.NotPendingFlag) {
                                    toastr.info("TaskID = " + result2.saveNotPending + " not pending! cannnot delete");
                                    return false;
                                }
                                // delete
                                $.ajax({
                                    type: "get",
                                    url: "../functions/atsController.php?do=deleteAtsInfoByMultiTaskId",
                                    data: {multiTask: ckArr},
                                    // dataType: 'json',
                                    success: function (result2) {
                                        if("done" == result2){
                                            toastr.success("success deleted");
                                            $('#taskTable').bootstrapTable('selectPage', 1);
                                        } else {
                                            toastr.error(result2);
                                        }
                                    },
                                    error: function () {
                                        toastr.error("fail deleted");
                                    }
                                });
                            },
                            error: function (xhr,status,error) {
                                toastr.error(xhr.status + " " + xhr.statusText);
                            }
                        });
                    }
                }
            });
        } else {
            toastr.warning("Please select not more than five checkbox");
            return false;
        }
    });
};

function addFormValidatorInit(){
    $('#addTaskForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            machine_name: {
                message: 'the testMachine is not valid',
                validators: {
                    notEmpty: {
                        message: 'The testMachine is required and can\'t be empty'
                    }
                }
            },
            dmi_product_name: {
                message: 'the Product Name is not valid',
                validators: {
                    notEmpty: {
                        message: 'The product name is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 5,
                        max: 30,
                        message: 'The product name must be more than 5 and less than 20 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                        message: 'The product name can only consist of special character like ~!@#$%^&*-/, , number, en.'
                    }

                }
            },
            dmi_serial_number: {
                message: 'the sn is not valid',
                validators: {
                    notEmpty: {
                        message: 'The sn is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 5,
                        max: 20,
                        message: 'The sn must be more than 5 and less than 20 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                        message: 'The sn can only consist of special character like ~!@#$%^&*-/, , number, en.'
                    }

                }
            },
            dmi_part_number: {
                message: 'the pn is not valid',
                validators: {
                    notEmpty: {
                        message: 'The pn is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 5,
                        max: 30,
                        message: 'The pn must be more than 5 and less than 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                        message: 'The pn can only consist of special character like ~!@#$%^&*-/, , number, en.'
                    }

                }
            },
            dmi_oem_string: {
                // enabled: false,
                message: 'the oem is not valid',
                validators: {
                    notEmpty: {
                        message: 'The oem is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 5,
                        max: 100,
                        message: 'The oem must be more than 5 and less than 100 characters long'
                    },
                    regexp: {
                        // regexp: /^[a-zA-Z0-9_\. \u4e00-\u9fa5 ]+$/,
                        regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                        message: 'The oem can only consist of special character like ~!@#$%^&*-/, , number, en.'
                    }

                }
            },
            dmi_system_config: {
                // enabled: false,
                message: 'the sytsem config is not valid',
                validators: {
                    notEmpty: {
                        message: 'The sytsem config is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 1,
                        max: 20,
                        message: 'The sytsem config must be more than 1 and less than 20 characters long'
                    },
                    regexp: {
                        // regexp: /^[a-zA-Z0-9_\. \u4e00-\u9fa5 ]+$/,
                        regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                        message: 'The sytsem config can only consist of special character like ~!@#$%^&*-/, , number, en.'
                    }

                }
            }
        }

    }).on('success.form.bv', function (e) {
        // Prevent form submission
        e.preventDefault();

        // Get the form instance
        var $form = $(e.target);

        // Get the BootstrapValidator instance
        var bv = $form.data('bootstrapValidator');

        var machineId = testMachine.select2('data')[0].text;
        console.log(machineId);
        var testMachineVal = testMachine.val();
        console.log(testMachineVal);
        if (null != machineId) {
            machineId = machineId.replace(testMachineVal, '');
            machineId = machineId.substring(1, machineId.length - 1);
        }

        var formSerialize = $form.serialize() + '&machine_id=' + machineId;

        // Use Ajax to submit form data
        $.ajax({
            type : "post",
            url: "{:url('TaskManager/addTask')}",
            data: {
                formSerialize
            },
            success : function (result) {
                console.log(result);
                if (result == "success") {
                    toastr.success('add success!');

                }
            },
            error : function(xhr,status,error){
                toastr.error('add fail! try again ');
            },
            complete : function () {
                setTimeout("window.location.href=\"TaskManager\"",1500);

            }
        });
    });

};