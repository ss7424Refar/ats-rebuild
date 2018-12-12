<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-11
 * Time: 下午4:18
 */

namespace app\services\controller;

use think\Controller;
use think\Db;

/*
 * interface for ats
 */
class MachineDetail extends Controller {
    /*
     * http://localhost/ats/server/MachineDetail/getMachineInfo?fix_no=0512013
     */
    public function getMachineInfo(){
        $no = $this->request->param('fix_no');

        $res = Db::query('SELECT * FROM itd.d_main_engine where fixed_no = ?;', [$no]);

        return json_encode($res, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

}