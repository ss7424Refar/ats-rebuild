<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../resource/img/3.ico"/>
    <title>Automation Test System | Starter</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
    <!--PACE-->
    <link rel="stylesheet" href="../plugins/pace/pace.css">
    <!--    bootstrap dataTables-->
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap-table-develop/dist/bootstrap-table.min.css">
    <!--    bootstrap dataTables extensions-->
    <link rel="stylesheet" href="../plugins/bootstrap-table-develop/src/extensions/page-jumpto/bootstrap-table-jumpto.css">
    <!--    toastr-->
    <link href="../plugins/CodeSeven-toastr/build/toastr.min.css" rel="stylesheet" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/iCheck/all.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
    <!--    bootstrapValidator-->
    <link href="../plugins/bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="../dist/js/html5shiv.min.js"></script>
    <script src="../dist/js/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="../dist/css/googleFont.css">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<!--<body class="hold-transition skin-blue sidebar-mini">-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- Main Header -->
    <?php include 'header.php';?>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview" >
                    <a href="#"><i class="fa fa-wrench"></i> <span>Add Task</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="addTaskJumpStart.php"><i class="fa fa-circle-o text-yellow"></i> Jump Start</a></li>
<!--                        <li><a href="addTaskTreboot.php"><i class="fa fa-circle-o text-aqua"></i> Treboot</a></li>-->
                    </ul>
                </li>
                <li class="header">INFO</li>
                <li class="treeview active" >
                    <a href="#"><i class="fa fa-link"></i> <span>Task Manager</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="taskManagerForJump.php"><i class="fa fa-circle-o text-red"></i> Jump Start</a></li>
                    </ul>
                </li>
                <li class="header">CHECK OUT</li>
                <li>
                    <a href="portCheck.php"><i class="fa fa-link"></i> <span>Port Status</span></a>
                </li>

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Task Manager
                <small>For Auto Tool</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="atsIndex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Task Manager</li>
                <li class="active">Jump Start</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header  with-border" style="padding-top: 20px">
                            <div class="callout callout-info">
                                <strong><i class="fa fa-hand-o-right fa-fw"></i>&nbsp;Info: </strong><i>&nbsp;You can double click table row to see DMI info.</i>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="toolbar" class="btn-toolbar" role="toolbar">
                                <div class="btn-group" role="group" >
<!--                                    <button type="button" class="btn btn-primary btn-sm" id="task"><i class="fa fa-tasks"></i>&nbsp;Task</button>-->
                                    <button type="button" class="btn btn-primary btn-sm" id="task"><i class="glyphicon glyphicon-th-list"></i>&nbsp;Task</button>
                                </div>

                                <div class="btn-group" role="group" style="display: none">
<!--                                    <button type="button" class="btn btn-success btn-sm" id="addTask" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus fa-fw"></i>&nbsp;Add</button>-->
                                    <button type="button" class="btn btn-warning btn-sm" id="assignTask"><i class="fa fa-wrench fa-fw"></i>&nbsp;Assign</button>
                                    <button type="button" class="btn btn-info btn-sm" id="editTask" data-toggle="modal"><i class="fa fa-edit fa-fw"></i>&nbsp;Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="deleteTask"><i class="fa fa-close  fa-fw"></i>&nbsp;Delete</button>
<!--                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-copy fa-fw"></i>&nbsp;Copy</button>-->
                                </div>
                                <!--                <button class="btn btn-warning pull-right btn-sm" href="#"><i class="fa fa-sync fa-spin fa-fw" aria-hidden="true"></i></button>-->
                            </div>
                            <table id="taskTable" class="" data-show-jumpto="true"></table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit 模态框（Modal） -->
            <div class="modal fade" id="editModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Edit Test Task</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal form-group-sm" id="editForm">
                                <input type="text" hidden="hidden">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Test Machine</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" disabled="disabled">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Test Image</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="testImage">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="TestDMIReset" class="col-sm-3 control-label">TestDMIReset</label>
                                    <div class="col-sm-7">
                                        <div class="btn btn-default btn-sm">getDefaultDMI</div>
                                        <input type="text" hidden="hidden">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="editProduct">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Serial Number</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="editSN">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Part Number</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="editPN">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">OEM String</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="editOem">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">SystemConfig</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="editSystem">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">LANIP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ShelfID_SwitchID</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
            </div>
            <!--        dmi info-->
            <div class="modal fade" id="dmiInfo"  role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">DMI INFO</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal form-group-sm">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Task ID</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static">Task ID</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Serial Number</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Part Number</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">OEM String</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">System Config</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-static"></p>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">close</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <?php include 'footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!--PACE-->
<script src="../plugins/pace/pace.js"></script>
<!--    bootstrap dataTables-->
<script type="text/javascript" src="../plugins/bootstrap-table-develop/dist/bootstrap-table.min.js"></script>
<script type="text/javascript" src="../plugins/bootstrap-table-develop/dist/locale/bootstrap-table-en-US.min.js"></script>
<!--    bootstrap dataTables extensions-->
<link rel="stylesheet" href="../plugins/bootstrap-table-develop/src/extensions/page-jumpto/bootstrap-table-jumpto.css">
<script src="../plugins/bootstrap-table-develop/src/extensions/page-jumpto/bootstrap-table-jumpto.js"></script>
<!--    toastr-->
<script src="../plugins/CodeSeven-toastr/build/toastr.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- bootbox4.4 -->
<script src="../plugins/bootbox4.4/bootbox.min.js"></script>
<!-- Select2 -->
<script src="../bower_components/select2/dist/select2.min.js"></script>
<!--    bootstrapValidator-->
<script src="../plugins/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {
        // toastr
        toastrInit();

        //Task
        taskButton();


        // table
        tableInit(queryParams);

        // select2
        select2Init();

         // assign
        assignButtonInit();

        // delete
        deleteTaskButtonInit();

        //edit
        editButtonInit();

        editFormInit();

    });

    function  toastrInit() {

        // toastr
        toastr.options.positionClass = 'toast-top-center';
        toastr.options.closeButton = true;
        // css
        // toastr.options.positionClass = 'toast-center-center';

        toastr.options.timeOut = 2000; // How long the toast will display without user interaction
        toastr.options.extendedTimeOut = 2000; // How long the toast will display after a user hovers over it

        // toastr.options.progressBar = true;
    };

    function taskButton(){
        var buttonGroup=$('.btn-group:eq(1)');
        var time=null;

        $('.btn-group:eq(0), .btn-group:eq(1)').mouseenter(function () {
            clearTimeout(time);
            buttonGroup.css('display', 'block');
        });

        // Task
        $('.btn-group:eq(0), .btn-group:eq(1)').mouseleave(function () {
            time=setTimeout(function(){
                buttonGroup.css('display', 'none');
            },300);
        });
    };

    function tableInit(queryPs){
        $('#taskTable').bootstrapTable({
            url: '../functions/atsTaskInfoPageInit.php',         //请求后台的URL（*）
            method: 'get',                      //请求方式（*）
            classes: 'table table-responsive table-hover table-no-bordered', // table 样式
            iconSize: 'sm',
            buttonsClass: 'warning',
            toolbar: '#toolbar',                //工具按钮用哪个容器
            striped: true,                      //是否显示行间隔色
            cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
            pagination: true,                   //是否显示分页（*）
            // sortable: true,                     //是否启用排序
            // sortOrder: "asc",                   //排序方式
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
            uniqueId: "TaskID",                     //每一行的唯一标识，一般为主键列
            showToggle:false,                    //是否显示详细视图和列表视图的切换按钮
            cardView: false,                    //是否显示详细视图
            detailView: false,                   //是否显示父子表
            columns: [{
                checkbox: true
            }, {
                field: 'TaskID',
                title: 'TaskID',
                formatter:function(value, row, index){
                    return 'ATS_' + value;
                }
            }, {
                field: 'TestMachine',
                title: 'Test Machine',
                formatter:function(value, row, index){
                    return value;
                }
            }, {
                field: 'TestImage',
                title: 'Test Image'
            }, {
                field: 'MachineID',
                title: 'MachineID'
            }, {
                field: 'TestItem',
                title: 'Assigned Task'
            }, {
                field: 'TaskStatus',
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
                    }
                    return "N/A";
                }
            }, {
                field: 'TestStartTime',
                title: 'StartDate',
            }, {
                field: 'TestEndTime',
                title: 'FinishDate'
            }, {
                field: 'TestResult',
                title: 'Test Result',
                formatter: function(value, row, index){
                    if("Fail"==value){
                        return '<a href="\\\\172.30.52.28\\JSDataBK\\#Temp\@ATS_Results"><i class="fa fa-times fa-fw"></i>&nbsp;' + value + '</a>';
                    } else if("Pass"==value){
                        return '<a href="\\172.30.52.28\JSDataBK\#Temp@ATS_Results"><i class="fa fa-check fa-fw"></i>&nbsp;' + value + '</a>';
                    }
                    return "N/A";
                    // return "<a href=" +  + "></a>";
                }
            }
            ],
            rowStyle: function(row, index){
                if("Fail"==row.TestResult){
                    return {classes: 'danger'};
                }else if("Pass"==row.TestResult){
                    return {classes: 'success'};
                }else if(0==row.TaskStatus){
                    return {classes: 'info'};
                }else if(1==row.TaskStatus){
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
            var taskId=$element.TaskID;
            console.log(taskId);
            $.ajax({
                type: "get",
                url: "../functions/atsController.php?do=getAtsInfoByTaskId&" + "taskID=" + taskId,
                dataType: "json",
                success: function (result) {
                    console.log(result.flag);
                    if(result.flag){
                        $("#dmiInfo").find('p:eq(0)').html(result.row['TaskID']);
                        $("#dmiInfo").find('p:eq(1)').html(result.row['DMI_ProductName']);
                        $("#dmiInfo").find('p:eq(2)').html(result.row['DMI_SerialNumber']);
                        $("#dmiInfo").find('p:eq(3)').html(result.row['DMI_PartNumber']);
                        $("#dmiInfo").find('p:eq(4)').html(result.row['DMI_OEMString']);
                        $("#dmiInfo").find('p:eq(5)').html(result.row['DMI_SystemConfig']);
                        $("#dmiInfo").modal("toggle");
                    }
                    else{
                        toastr.info("TaskID = " + taskId + " didn't found! Please Refresh Table!");
                    }
                },
                error:function (xhr,status,error) {
                    toastr.error(xhr.status + " " + xhr.statusText);
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

    function  select2Init() {
        $("select[name='testImage']").select2({
                width: "100%",
                ajax: {
                    url: "../functions/atsController.php?do=getImageName4Select2",
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

    function editButtonInit() {
        $('#editTask').click(function () {
            var ckArr = $('#taskTable').bootstrapTable('getSelections');
            // console.log(ckArr[0].TaskID);
            if(ckArr.length == 0){
                toastr.info("Please select at least one checkbox");
                return false;
            }
            if(ckArr.length > 1){
                toastr.info("Please only check one checkbox");
                return false;
            }else{
                // checkPending
                $.ajax({
                    type: "get",
                    url: "../functions/atsController.php?do=checkAtsInfoByMultiTaskId",
                    data: {multiTask: ckArr},
                    dataType: 'json',
                    success: function (result) {
                        console.log(result.NoTaskIdFlag);
                        console.log(result.NotPendingFlag);

                        if (result.NoTaskIdFlag) {
                            toastr.info("TaskID = " + result.saveNoTaskId + " didn't found! Please Refresh Table!");
                            return false;
                        }
                        if (result.NotPendingFlag) {
                            toastr.info("TaskID = " + result.saveNotPending + " not pending! cannnot edit");
                            return false;
                        }

                        $('#editForm').data('bootstrapValidator').destroy();
                        editFormInit();

                        // show updateData
                        $.ajax({
                            type: "get",
                            url: "../functions/atsController.php?do=getAtsInfoByTaskId&" + "taskID=" + ckArr[0].TaskID,
                            dataType: "json",
                            success: function (result) {
                                console.log(result.flag);
                                if(result.flag){
                                    $("#editForm").find('input:eq(0)').val(result.row['TaskID']);
                                    $("#editForm").find('input:eq(1)').val(result.row['TestMachine']);
                                    $("#editForm").find('input:eq(3)').val(result.row['DMI_ProductName']);
                                    $("#editForm").find('input:eq(4)').val(result.row['DMI_SerialNumber']);
                                    $("#editForm").find('input:eq(5)').val(result.row['DMI_PartNumber']);
                                    $("#editForm").find('input:eq(6)').val(result.row['DMI_OEMString']);
                                    $("#editForm").find('input:eq(7)').val(result.row['DMI_SystemConfig']);
                                    $("#editForm").find('input:eq(8)').val(result.row['LANIP']);
                                    $("#editForm").find('input:eq(9)').val(result.row['ShelfID'] + '_' + result.row['SwitchId']);

                                    var option = new Option(result.row['TestImage'], result.row['TaskID'], true, true);
                                    $("select[name='testImage']").append(option).trigger('change');

                                    $('#editModal').modal("show")

                                }
                                else{
                                    toastr.info("TaskID = " + taskId + " didn't found! Please Refresh Table!");
                                }
                            },
                            error:function (xhr,status,error) {
                                toastr.error(xhr.status + " " + xhr.statusText + "Get Update Data Failed");
                            }
                        });
                    },
                    error: function (xhr,status,error) {
                        toastr.error(xhr.status + " " + xhr.statusText+ "CheckPending Data Failed");
                    }
                });

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
                                type: "get",
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

    function editFormInit() {
        $('#editForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                testImage: {
                    message: 'the testImage is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The testImage is required and can\'t be empty'
                        }
                    }
                },
                editProduct: {
                    message: 'the Product Name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The product name is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: 'The product name must be more than 5 and less than 20 characters long'
                        }
                    }
                },
                editSN: {
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
                            regexp: /^[a-zA-Z0-9_~!@#$%^&*-/,]+$/,
                            message: 'The sn can only consist of special character like ~!@#$%^&*-/, , number, en.'
                        }

                    }
                },
                editPN: {
                    message: 'the pn is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The pn is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: 'The pn must be more than 5 and less than 20 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_~!@#$%^&*-/,]+$/,
                            message: 'The pn can only consist of special character like ~!@#$%^&*-/, , number, en.'
                        }

                    }
                },
                editOem: {
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
                            regexp: /^[a-zA-Z0-9_~!@#$%^&*-/,]+$/,
                            message: 'The oem can only consist of special character like ~!@#$%^&*-/, , number, en.'
                        }

                    }
                },
                editSystem: {
                    // enabled: false,
                    message: 'the sytsem config is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The sytsem config is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 1,
                            max: 20,
                            message: 'The sytsem config must be more than 5 and less than 100 characters long'
                        },
                        regexp: {
                            // regexp: /^[a-zA-Z0-9_\. \u4e00-\u9fa5 ]+$/,
                            regexp: /^[a-zA-Z0-9_~!@#$%^&*-/,]+$/,
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
            // var bv = $form.data('bootstrapValidator');

            $.ajax({
                type: "get",
                url: "../functions/atsController.php?do=updateTaskById",
                data: {
                    editId: $("input[hidden=hidden]:eq(0)").val(),
                    editImage: $("select[name='testImage']").select2('data')[0].text,
                    customer: $("input[hidden=hidden]:eq(1)").val(),
                    editProduct: $('#editForm').find('input:eq(3)').val(),
                    editSN: $('#editForm').find('input:eq(4)').val(),
                    editPN: $('#editForm').find('input:eq(5)').val(),
                    editOem:$('#editForm').find('input:eq(6)').val(),
                    editSystem: $('#editForm').find('input:eq(7)').val(),
                },
                dataType: 'json',
                success: function (result) {
                    if (result >= 0 ){
                        toastr.success("success update");
                    } else {
                        toastr.error("update fail --> " + result2);
                    }
                },
                error: function () {
                    toastr.error("fail updated");
                },
                complete: function () {
                    $('#editModal').modal('hide');
                    $('#taskTable').bootstrapTable('selectPage', 1);
                }

            });


        });
    }

</script>

</body>
</html>


<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-7-26
 * Time: 上午10:51
 */