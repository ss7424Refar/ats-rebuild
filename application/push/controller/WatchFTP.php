<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-17
 * Time: 下午8:11
 */

namespace app\push\controller;

// 暂时不使用这个类
use think\Log;
use ext\FTPUtil;

class WatchFTP {

    public function dog() {

        $ftpUtil = new FTPUtil(config('HOST_NAME'), config('HOST_USER'), config('HOST_PASS'));

        $localFile = ATS_PREPARE_PATH. ATS_PREPARE_FILE. ATS_FILE_suffix;
        $ftpFile = ATS_FTP_PATH. ATS_PREPARE_FILE. ATS_FILE_suffix;

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