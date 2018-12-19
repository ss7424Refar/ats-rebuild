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
define('THEME', 'hold-transition skin-purple sidebar-collapse sidebar-mini');

/*
 * tool name
 */

define('JUMP_START', 'JumpStart');
define('RECOVERY', 'Recovery');
define('C_TEST', 'C-Test');

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
//define('ATS_FINISH_PATH', '/home/refar/Phpproject/ats_sizu/resource/finish/');

/*
 * timer type of chart
 */

define('HOUR', 'hour');
define('DAY', 'day');
define('WEEK', 'week');
define('MONTH', 'month');
define('YEAR', 'year');

/*
 * 测试结果或者状态
 */

define('PASS', 'pass');
define('FAIL', 'fail');
define('ONGOING', 'ongoing');
define('PENDING', 'pending');
define('EXPIRED', 'expired');
define('FINISHED', 'finished');