<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="resource/img/3.ico"/>
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
    <style>
        /*.select2-results__option[aria-selected=true]{*/
            /*display:none;*/
        /*}*/

    </style>
    <!--PACE-->
    <link rel="stylesheet" href="../plugins/pace/pace.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/iCheck/all.css">
    <!--    bootstrapValidator-->
    <link href="../plugins/bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet">

    <!--    toastr-->
    <link href="../plugins/CodeSeven-toastr/build/toastr.min.css" rel="stylesheet" />

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
<body class="hold-transition skin-blue sidebar-mini">
<!--<body class="hold-transition skin-blue layout-boxed">-->
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
                <li class="treeview active" >
                    <a href="addTaskJumpStart.php"><i class="fa fa-wrench"></i> <span>Add Task</span>
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
                Add Task
                <small>For Jump Start</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="atsIndex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Add Task</li>
                <li class="active">Jump Start</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container">
            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3>Task Info</h3>
                        </div>
                        <form role="form"  class="form-horizontal" id="addTaskForm">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Test Machine</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="testMachine">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Test Image</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="testImage"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">TestDMIReset</label>
                                    <div class="col-sm-9" style="padding-top: 7px;padding-left: 14px">
                                        <label style="margin-right: 19px">
                                            <input type="radio" name="customer" value="default" /> default
                                        </label>
                                        <label>
                                            <input type="radio" name="customer" value="customer" /> customer
                                        </label>
                                    </div>
                                </div>
                                <div style="display:none" data-topic="pDmiInfo">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Product Name</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Serial Number</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Part Number</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">OEM String</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">SystemConfig</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">LANIP</label>
                                        <div class="col-sm-3">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">ShelfID_SwitchID</label>
                                        <div class="col-sm-3">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>

                                </div>
                                <div style="display:none;" data-topic="inputDmiInfo">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Product Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addProduct" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Serial Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addSN" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Part Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addPN" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">OEM String</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addOem" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">SystemConfig</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addSystem" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">LANIP</label>
                                        <div class="col-sm-3">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">ShelfID_SwitchID</label>
                                        <div class="col-sm-3">
                                            <p class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-md-10">
                                    <a class="btn btn-default pull-right" onclick="window.location.href='atsIndex.php';">Back</a>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </section>
        <!-- /.content -->
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
<!-- Select2 -->
<script src="../bower_components/select2/dist/select2.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!--    bootstrapValidator-->
<script src="../plugins/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
<!--    toastr-->
<script src="../plugins/CodeSeven-toastr/build/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {

        // ----------------------- dataInit -----------------------
        testImage = $("select[name='testImage']");
        testMachine = $("select[name='testMachine']");

        pDmiInfo = $('div[data-topic="pDmiInfo"]');
        pDmiInfo_product = $('div[data-topic="pDmiInfo"] p:eq(0)');
        pDmiInfo_sn = $('div[data-topic="pDmiInfo"] p:eq(1)');
        pDmiInfo_pn = $('div[data-topic="pDmiInfo"] p:eq(2)');
        pDmiInfo_oem = $('div[data-topic="pDmiInfo"] p:eq(3)');
        pDmiInfo_sys = $('div[data-topic="pDmiInfo"] p:eq(4)');
        pDmiInfo_lanIp = $('div[data-topic="pDmiInfo"] p:eq(5)');
        pDmiInfo_shelfId = $('div[data-topic="pDmiInfo"] p:eq(6)');

        inputDmiInfo = $('div[data-topic="inputDmiInfo"]');
        inputDmiInfo_lanIp = $('div[data-topic="inputDmiInfo"] p:eq(0)');
        inputDmiInfo_shelfId = $('div[data-topic="inputDmiInfo"] p:eq(1)');

        addCK = $('input[name="customer"]');
        addDefaultCK = $('input[name="customer"]:eq(0)');
        addCustomerCK = $('input[name="customer"]:eq(1)');
        // ----------------------- dataInit -----------------------

        toastrInit();

        select2Init();

        iCheckInit();

        addFormValidatorInit();

        // init form
        addDefaultCK.iCheck('check');
        testMachine.val(null).trigger('change');
        testImage.val(null).trigger('change');
        // pDmiInfo.css('display', 'none');
        // inputDmiInfo.css('display', 'none');
        $('#addTaskForm').data('bootstrapValidator').destroy();
        addFormValidatorInit();

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

    function  select2Init() {
        testImage.select2({
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

        testMachine.select2({
            width: "100%",
            ajax: {
                // url: 'function/readAddData.php',
                url: "../functions/atsController.php?do=readMachine4Select2",
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
            placeholder:'Please Select',
            allowClear:true
        });


        testMachine.on("select2:select",function(e){
            console.log(testMachine.val());
            var testMachineVal = testMachine.val();
            var machineId = testMachine.select2('data')[0].text;
            if (null != machineId) {
                machineId = machineId.replace(testMachineVal, '');
                machineId = machineId.substring(1, machineId.length - 1);
            }
            console.log(machineId);
            // var data = e.params.data;
            // console.log(data.text);
            if (addDefaultCK.prop('checked')){
                $.ajax({
                    type: 'get',
                    url: "../functions/atsController.php?do=readTestPCInfo",
                    data: {machineId: machineId},
                    dataType: 'json',
                    success: function(result){
                        pDmiInfo.css('display', 'block');
                        pDmiInfo_product.html(result[0].product);
                        pDmiInfo_sn.html(result[0].sn);
                        pDmiInfo_pn.html(result[0].pn);
                        pDmiInfo_oem.html(result[0].oem);
                        pDmiInfo_sys.html(result[0].sys);
                        pDmiInfo_lanIp.html(result[0].lanIp);
                        pDmiInfo_shelfId.html(result[0].shelfId);
                    },
                    error: function () {
                        toastr.error("Dmi Read Failed");
                    }
                });
            } else {
                $.ajax({
                    type: 'get',
                    url: "../functions/atsController.php?do=readTestPCInfo",
                    data: {machineId: machineId},
                    dataType: 'json',
                    success: function(result){
                        inputDmiInfo_lanIp.html(result[0].lanIp);
                        inputDmiInfo_shelfId.html(result[0].shelfId);
                    },
                    error: function () {
                        toastr.error("Dmi Read Failed");
                    }
                });


            }
        });
        testMachine.on("select2:clear", function (e) {
            if (addDefaultCK.prop('checked')){
                pDmiInfo.css('display', 'none').find('p').html('');
            } else {
                inputDmiInfo.find('p').html('N/A');
            }
        });

    };

    function iCheckInit() {
        $('input[name=customer]').iCheck({
            radioClass: 'iradio_square-blue',
            increaseArea : '20%'
        }).on('ifChecked', function () {
            // alert($(this).val());
            var vadio = $(this).val();
            var machineId = testMachine.val();

            $('#addTaskForm').data('bootstrapValidator').destroy();

            if ("customer" === vadio) {
                // alert(1);
                pDmiInfo.css('display', 'none');
                inputDmiInfo.css('display', 'block');

                testMachine.val(null).trigger('change');
                inputDmiInfo.find('input').val('');
                inputDmiInfo.find('p').html('N/A');
            } else if("default" === vadio) {
                // alert(2);
                testMachine.val(null).trigger('change');
                inputDmiInfo.css('display', 'none');
                pDmiInfo.css('display', 'none');
            }
            addFormValidatorInit();
        });
    }


    function addFormValidatorInit(){
        $('#addTaskForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                testMachine: {
                    message: 'the testMachine is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The testMachine is required and can\'t be empty'
                        }
                    }
                },
                testImage: {
                    message: 'the testImage is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The testImage is required and can\'t be empty'
                        }
                    }
                },
                addProduct: {
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
                addSN: {
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
                addPN: {
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
                            regexp: /^[a-zA-Z0-9_~! @#$%^&*-/,]+$/,
                            message: 'The pn can only consist of special character like ~!@#$%^&*-/, , number, en.'
                        }

                    }
                },
                addOem: {
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
                addSystem: {
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

            var addMachine = testMachine.select2('data')[0].text;
            var testItem = "JumpStart";
            var addImage = testImage.select2('data')[0].text;
            console.log(addImage + ',' + addMachine);
            var testMachineVal = testMachine.val();
            var machineId = addMachine;
            if (null != machineId) {
                machineId = machineId.replace(testMachineVal, '');
                machineId = machineId.substring(1, machineId.length - 1);
            }

            if (addDefaultCK.prop('checked')){
                // Use Ajax to submit form data
                $.ajax({
                    type : "get",
                    url: "../functions/atsController.php?do=addTask",
                    data: {
                        testMachine: addMachine,
                        machineId: machineId,
                        testItem: testItem,
                        testImage: addImage,
                        customer: 'default',
                        addProduct: pDmiInfo.find('p:eq(0)').text(),
                        addSN: pDmiInfo.find('p:eq(1)').text(),
                        addPN: pDmiInfo.find('p:eq(2)').text(),
                        addOem:pDmiInfo.find('p:eq(3)').text(),
                        addSystem: pDmiInfo.find('p:eq(4)').text(),
                        lanIp: pDmiInfo.find('p:eq(5)').text(),
                        shelf: pDmiInfo.find('p:eq(6)').text()
                    },
                    success : function (result) {
                        console.log(result);
                        if (result == "success") {
                            toastr.success('add success!');

                        } else {
                            toastr.error('add fail! try again ');
                        }
                    },
                    error : function(xhr,status,error){
                        toastr.error(xhr.status + ' add fail! ');
                    },
                    complete : function () {
                        setTimeout("window.location.href=\"taskManagerForJump.php\";",3000);

                    }
                });

            } else {
                // Use Ajax to submit form data
                $.ajax({
                    type : "get",
                    url: "../functions/atsController.php?do=addTask",
                    data: {
                        testMachine: addMachine,
                        machineId: machineId,
                        testItem: testItem,
                        testImage: addImage,
                        customer: 'customer',
                        addProduct: inputDmiInfo.find('input:eq(0)').val(),
                        addSN: inputDmiInfo.find('input:eq(1)').val(),
                        addPN: inputDmiInfo.find('input:eq(2)').val(),
                        addOem: inputDmiInfo.find('input:eq(3)').val(),
                        addSystem: inputDmiInfo.find('input:eq(4)').val(),
                        lanIp: inputDmiInfo.find('p:eq(0)').text(),
                        shelf: inputDmiInfo.find('p:eq(1)').text(),
                    },
                    success : function (result) {
                        console.log(result);
                        if (result == "success") {
                            toastr.success('add success!');

                        } else {
                            toastr.error('add fail! try again ');
                        }
                    },
                    error : function(xhr,status,error){
                        toastr.error(xhr.status + ' add fail! ');
                    },
                    complete : function () {
                        setTimeout("window.location.href=\"taskManagerForJump.php\";",3000);

                    }
                });

            }
        });

    };
</script>
</body>
</html>