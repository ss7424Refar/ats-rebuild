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
    <!--    toastr-->
    <link href="../plugins/CodeSeven-toastr/build/toastr.min.css" rel="stylesheet" />
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="../dist/css/skins/skin-blue.css">

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
                    <a href="#"><i class="fa fa-wrench"></i> <span>Auto Tool</span>
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
                <li class="treeview" >
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
                <li class="active">
                    <a href="portCheck.php"><i class="fa fa-link"></i> <span>Port Status</span></a>
                </li>

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Port Status
                <small>For Check</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="atsIndex.php"><i class="fa fa-dashboard"></i> Home</a></li>
<!--                <li>Auto Tool</li>-->
                <li class="active">Port Status</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header  with-border" style="padding-top: 20px">
                            <div class="alert bg-teal color-palette alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<!--                                <h4> Alert!</h4>-->
                                <i><i class="icon fa fa-linux fa-1x" style="color: black"></i> You are checking Port Status. The Using Shelf as bellows</i>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box box-solid">
                                <div class="col-md-6" style="height:350px;border-style: solid; border-width: 1px; border-color: #DDDDDD;overflow: scroll;">
                                    <ul class="list-unstyled" id="list">
                                        <li ><span class="text-warning"><i>ATS console detect log for TestPC</i></span></li>

                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Machine</th>
                                                <th>MachineId</th>
                                                <th>LanIp</th>
                                                <th>Shelf_SwitchId</th>
                                            </tr>
                                        </thead>
                                        <tbody id="content">
                                            <tr><td colspan="5">
                                                <span class="text-warning">
                                                    <i><i class="fa fa-spinner fa-pulse" style="color: #0A8CC6"></i> No data detect, Please wait 10s ...</i>
                                                </span>
                                                </td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
<!--    toastr-->
<script src="../plugins/CodeSeven-toastr/build/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {

        // toastr
        toastr.options.positionClass = 'toast-top-center';
        toastr.options.closeButton = true;
        // css
        // toastr.options.positionClass = 'toast-center-center';

        toastr.options.timeOut = 2000; // How long the toast will display without user interaction
        toastr.options.extendedTimeOut = 2000; // How long the toast will display after a user hovers over it

        // toastr.options.progressBar = true;

        WebSocketInit();

    });

    function WebSocketInit()
    {
        if ("WebSocket" in window)
        {

            // 打开一个 web socket
            var ws = new WebSocket("ws://192.168.0.100:8080/");

            ws.onopen = function()
            {
                // Web Socket 已连接上，使用 send() 方法发送数据
                // ws.send("发送数据");
                // alert("send data...");
            };

            ws.onmessage = function (evt)
            {
                // console.log(evt.data);
                var response = evt.data;
                if (null != response) {
                    response = JSON.parse(response);
                    console.log(response.message);
                    // list
                    $('#list').append('<li><span class="text-info small"><i>' + response.message + '</i></span></li>');

                    // table
                    console.log(response.result);
                    var tableData = response.result;
                    $('#content').empty();
                    if (null != tableData){
                        for (var i = 0; i < tableData.length; i++) {
                            $('#content').append('<tr></tr>');
                            // $('#content tr:last').append('<td>' + tableData[i].id + '</td>');
                            $('#content tr:last').append('<td>' + tableData[i].machine + '</td>');
                            $('#content tr:last').append('<td>' + tableData[i].machineId + '</td>');
                            $('#content tr:last').append('<td>' + tableData[i].LANIP + '</td>');
                            $('#content tr:last').append('<td>' + tableData[i].ShelfId_SwitchId + '</td>');
                        }
                    }

                }

            };

            ws.onclose = function()
            {
                // 关闭 websocket
                setTimeout("toastr.success(\"ws connect close...\");", 80);
            };
        }

        else
        {
            // 浏览器不支持 WebSocket
            alert("your browse not support WebSocket!");
        }
    }
</script>

</body>
</html>


<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-7-27
 * Time: 下午5:36
 */

