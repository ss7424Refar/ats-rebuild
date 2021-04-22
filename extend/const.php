<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 下午4:59
 */

define('ATS_VERSION', '1.6.0.2');

/*
 * adminLTE theme
 */
define('THEME', 'hold-transition skin-purple layout-top-nav');

/*
 * tool name
 */

define('JUMP_START', 'JumpStart');
define('RECOVERY', 'Recovery');
define('C_TEST', 'C-Test');
define('TREBOOT', 'Treboot');
define('TAndD', 'TandD'); // Qiulin说要web那里小写, 我觉得TAndD更帅
define('FastBoot', 'FastBoot');
define('BIOSUpdate', 'BIOSUpdate');
define('MT', 'MT');
define('HCITest', 'HCITest');
define('CommonTool', 'CommonTool');
define('TREBOOTMS', 'Treboot-MS');
define('FASTBOOTMS', 'FastBoot-MS');
define('PATCH', 'Patch');
define('OPEN_CLOSE', 'Open-Close');

define('ToolName', json_encode([JUMP_START, RECOVERY, C_TEST, TREBOOT,
    TAndD, FastBoot, BIOSUpdate, MT, HCITest, CommonTool, TREBOOTMS, FASTBOOTMS, PATCH, OPEN_CLOSE]));
/*
 * ATS file path
 */

//define('ATS_RESULT_PATH', '\\\\172.30.52.28\\JSDataBK\\#Temp\\@ATS_Results\\');
define('ATS_RESULT_PATH', '\\\\172.30.184.28\\ATS_Results\\');
//define('ATS_RESULT_PATH', 'http://172.30.52.28:8089/'); // 使用python开启了httpServer

/*
 * timer type of chart
 */

define('HOUR', 'hour');
define('DAY', 'day');
define('WEEK', 'week');
define('MONTH', 'month');
define('YEAR', 'year');
define('BYMONTH', 'byMonth');

/*
 * 测试结果
 */

define('PASS', 'pass');
define('FAIL', 'fail');
define('ONGOING', 'ongoing');
define('PENDING', 'pending');
define('EXPIRED', 'expired');
define('FINISHED', 'finished');

/*
 * send mail
 */
define('ATS_URL', 'http://pcs.dbh.dynabook.com/');