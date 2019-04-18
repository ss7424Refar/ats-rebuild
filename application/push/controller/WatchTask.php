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

class WatchTask {

    public function dog() {

        $handler = opendir(config('ats_temp_task_path'));

        while (($fileName = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($fileName != "." && $fileName != "..") {
                $fileCreate = config('ats_temp_task_path'). $fileName;

                Log::record('cp '. $fileName);
                $cpRes = copy($fileCreate, config('ats_pe_task'). $fileName);

                Log::record('rm '. $fileName);
                $rmRes = unlink($fileCreate);

                if(1 != $cpRes){
                    Log::record('copy fail '. $fileName);
                    exit();
                }else if (1 != $rmRes){
                    Log::record('remove fail '. $fileName);
                    exit();
                } else {
                    Log::record('upload success '. $fileName);
                }

            }
        }

    }

}