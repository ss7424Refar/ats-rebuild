<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-8-30
 * Time: 下午4:59
 */

/*
 * tool name
 */

define('JUMP_START', 'JumpStart');
define('RECOVERY', 'Recovery');
define('C_TEST', 'C-Test');

/*
 * ATS path
 */

define('ATS_IMAGES_PATH', '/home/refar');
define('ATS_FILE_suffix', '.csv');
//define('ATS_PREPARE_PATH', '/home/refar/Phpproject/ats_sizu/resource/prepare/');
define('ATS_PREPARE_PATH', '/home/refar/PhpstormProjects/ats_sizu/resource/prepare/');
define('ATS_PREPARE_FILE', 'TestPC');
define('ATS_TMP_TASKS_PATH', '/home/refar/Phpproject/ATS/ats/resource/tmp/');
define('ATS_FILE_UNDERLINE', '_');
define('ATS_TMP_TASKS_HEADER', 'Task'. ATS_FILE_UNDERLINE);
define('ATS_TASKS_PATH', '/mnt/atsShare/Tasks/');
define('ATS_FINISH_PATH', '/home/refar/Phpproject/ats_sizu/resource/finish/');

/*
 * timer type of chart
 */

define('HOUR', 'hour');
define('TODAY', 'today');
define('WEEK', 'week');
define('MONTH', 'month');
define('YEAR', 'year');