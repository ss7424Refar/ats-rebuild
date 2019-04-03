<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-17
 * Time: 下午8:11
 */

namespace app\push\controller;

use think\Exception;
use ext\MailerUtil;
use think\Log;

class WatchCifs {

    private $path;

    public function __construct(){

        $this->path = EXTEND_PATH. 'cifs_mail.txt';
        $this->createFile();
    }

    public function dog() {

        $testPC = config('ats_pe_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix');

        try{
            $flag = file_exists($testPC);
            Log::record('[cifs dog] detect flag is '. $flag);

            if (1 != $flag) {
                $this->handlerDisconnect();
            } else {
                $this->handlerConnect();
            }
        }catch (Exception $e) {
            Log::record('[cifs dog] error message is '. $e->getMessage());
            // send mail
            $this->handlerDisconnect();
        }

    }

    private function createFile() {
        // 文件不存在则尝试创建之。可读又可以写
        if(!file_exists($this->path)) {
            $file = fopen($this->path, 'w+');

            fwrite($file, 'not send');
            fclose($file);

            chmod($this->path, 0777);
        }
    }

    private function handlerDisconnect() {
        $status = file_get_contents($this->path);
        if ('not send' == $status) {
            MailerUtil::send(config('cifs_notice'), null, 'CIFS connection failed!', ' connection failed!');
            file_put_contents($this->path, 'send');
        }
    }

    private function handlerConnect() {
        $status = file_get_contents($this->path);
        if ('send' == $status) {
            file_put_contents($this->path, 'not send');
        }
    }
}