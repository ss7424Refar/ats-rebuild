<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-20
 * Time: 上午9:54
 */

namespace app\test\controller;
use think\Controller;

class Test extends Controller {

    public function test1() {
        extraConfig('file_timestamp', '33', 'ftp_ini');
    }

}