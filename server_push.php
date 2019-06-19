<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-10
 * Time: 下午1:59
 */

define('APP_PATH', __DIR__ . '/application/');
// 如果你的应用比较简单，模块和控制器都只有一个，那么可以在应用公共文件中绑定模块和控制器
define('BIND_MODULE', 'push/PushMan');

// 自定义变量
//require __DIR__ . '/extend'. '/const.php';

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';