<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-17
 * Time: 下午8:11
 */

namespace app\push\controller;

use think\Log;
use ext\FTPUtil;

class WatchFTP {

    public function dog() {

        $ftpUtil = new FTPUtil(config('host_name'), config('host_user'), config('host_pass'));

        $localFile = config('ats_local_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix');
        $ftpFile = config('ats_ftp_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix');

        $flag = $ftpUtil->check_is_update($ftpFile);

        if ($flag) {
            Log::record('[ftp dog][check] local file modify time is not same as ftp file');
            if ($ftpUtil->down_file($ftpFile, $localFile)) {
                Log::record('[ftp dog][download] success');

            } else {
                Log::record('[ftp dog][download] fail');
            }

        } else {
            Log::record('[ftp dog][check] local file modify time same as ftp file');
        }

        $ftpUtil->ftp_bye();
    }

}