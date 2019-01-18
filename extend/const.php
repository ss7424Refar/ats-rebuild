<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 下午4:59
 */

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

/*
 * ATS file path
 */

define('ATS_IMAGES_PATH', '/home/refar');
define('ATS_FILE_suffix', '.csv');
define('ATS_PREPARE_PATH', ROOT_PATH. 'public/resource/prepare/');
define('ATS_PREPARE_FILE', 'TestPC');
define('ATS_TMP_TASKS_PATH', RUNTIME_PATH. 'output/');
define('ATS_FILE_UNDERLINE', '_');
define('ATS_TMP_TASKS_HEADER', 'Task'. ATS_FILE_UNDERLINE);
define('ATS_TASKS_PATH', ROOT_PATH. 'public/resource/tasks/');
define('ATS_RESULT_PATH', '\\\\172.30.52.28\\JSDataBK\\#Temp\\@ATS_Results\\');

/*
 * timer type of chart
 */

define('HOUR', 'hour');
define('DAY', 'day');
define('WEEK', 'week');
define('MONTH', 'month');
define('YEAR', 'year');

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
define('ATS_URL', 'http://172.30.52.43/ats/index/index/TaskManager');