<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-11-11
 * Time: 上午10:11
 */

namespace app\push\controller;

use think\Log;

class SyncFile {

    private $path;

    public function __construct(){

        $this->path = EXTEND_PATH. 'sync_flag.txt';
        $this->createFile();
    }

    public function dog() {

        $path = config('ats_bios_temp_update');

        $handler = opendir($path);

        while (($filename = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($filename != "." && $filename != "..") {

                $p = $path.DIRECTORY_SEPARATOR.$filename;

                // 是目录的情况
                if (is_dir($p)) {
                    // 判断flag
                    $status = file_get_contents($this->path);
                    if ('not upload' == $status) {
                        Log::record('[syncFile dog] starting sync '. $filename);
                        // 设置成uploading
                        file_put_contents($this->path, 'uploading');
                        // 同步
                        // - p 复制源文件内容后，还将把其修改时间和访问权限也复制到新文件中。
                        $cmd = 'cp -rfp ' . $p. ' ' . config('ats_bios_update') .' 2>&1';
                        exec($cmd, $out, $return_val);

                        if (0 != $return_val) {
                            Log::record('[syncFile dog] sync fail => '. $cmd. ' [Info] '. json_encode($out));

                        } else {
                            Log::record('[syncFile dog] sync success => '. $cmd. ' [Info] '. json_encode($out));
                            // 删除目录
                            $cmd = 'rm -rf ' . $p .' 2>&1';
                            exec($cmd, $out, $return_val);

                            if (0 != $return_val) {
                                Log::record('[syncFile dog] rm fail => '. $cmd. ' [Info] '. json_encode($out));
                            } else {
                                Log::record('[syncFile dog] rm success => '. $cmd. ' [Info] '. json_encode($out));
                            }
                        }

                        file_put_contents($this->path, 'not upload');
                    }

                }
            }
        }
    }

    private function createFile() {
        // 文件不存在则尝试创建之。可读又可以写
        if(!file_exists($this->path)) {
            $file = fopen($this->path, 'w+');

            fwrite($file, 'not upload');
            fclose($file);

            chmod($this->path, 0777);
        }
    }
}