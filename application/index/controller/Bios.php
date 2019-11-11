<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-11-7
 * Time: 下午2:23
 */

namespace app\index\controller;

use think\Log;

class Bios extends Common
{
    public function getNodeFile()
    {

        $pageSize = $this->request->param('limit');
        $offset = $this->request->param('offset');
        $search = $this->request->param('search');

        return $this->getFilesByPath(config('ats_bios_update'), $offset, $pageSize, $search);
    }

    public function getSubFileInfoByName() {
        $pageSize = $this->request->param('limit');
        $offset = $this->request->param('offset');
        $file = $this->request->param('file');

        return $this->getFilesByPath(config('ats_bios_update'). $file, $offset, $pageSize, null);
    }

    public function upload() {
        $file = request()->file('file')->getInfo();

        // 本地临时目录
        $filename = config('ats_bios_temp_update').$file['name'];

        // 上传 / 解压
        if (move_uploaded_file($file["tmp_name"], $filename)) {

            // 改权限
            chmod($filename, 0777);
            //获取解压后的目录名
            $extraFolder = config('ats_bios_temp_update'). substr($file['name'], 0, strlen($file['name']) - 4);

            if (!file_exists($extraFolder)) {
                mkdir($extraFolder, 0777);
            }

            // 直接调用命令行unzip
            // -d 解压到bios目录, -o覆盖不询问用户, -q不返回任何信息
            $cmd = 'unzip -oq ' . $filename. ' -d '. $extraFolder .' 2>&1';
            exec($cmd, $out, $return_val);
            Log::record('[unzip -oq] '. $filename. ' [Info] '. json_encode($out));

            if (0 != $return_val) {
                return json(array('code'=>500, 'msg'=>'Unzip File Fail'));
            }

            // 删除zip包
            unlink($filename);
            return json(array('code'=>200, 'msg'=>'Upload Success'));

            // 后面其实还有复制到ats服务器的代码, 但是从43到31网络好像会变的很卡
            // 代码响应会出现等待的情况, 所以选择启用workman从sync同步文件夹到31的方式
        }
    }

    public function delete() {
        $file = $this->request->param('file');

        $path = config('ats_bios_update'). $file;

        $cmd = 'rm -rf ' . $path .' 2>&1';
        exec($cmd, $out, $return_val);

        Log::record('[rm -rf] '. $cmd. ' [Info] '. json_encode($out));

        if (0 != $return_val) {
            return json(array('code'=>500, 'msg'=>'Delete Fail'));
        }

        return json(array('code'=>200, 'msg'=>'Delete Success'));
    }

    private function getFilesByPath($path, $offset, $pageSize, $search) {
        $handler = opendir($path);

        $result = array();
        $jsonResult = array();

        while (($filename = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($filename != "." && $filename != "..") {

                $p = $path.DIRECTORY_SEPARATOR.$filename;
                // 判断其他组的权限, 避免展开不了
                $others = substr($this->getChmod($p), -1);
                $dirFlag = is_dir($p);

                // 或者不是文件夹而是目录的情况
                if ($others >= 5 || !$dirFlag) {
                    if (empty(trim($search))) {
                        $tmpArray = array(
                            'file'=>$filename,
                            'size' => round((filesize($p)/1024),2),
                            'time'=>date("Y-m-d H:i:s",filemtime($p)),
                            'isDir'=>$dirFlag);
                        array_push($result, $tmpArray);
                    } else {
                        if (stristr($filename, $search) !== false) {
                            $tmpArray = array(
                                'file'=>$filename,
                                'size' => round((filesize($p)/1024),2),
                                'time'=>date("Y-m-d H:i:s",filemtime($p)),
                                'isDir'=>$dirFlag);
                            array_push($result, $tmpArray);
                        }
                    }
                }
            }
        }
        $time = array();
        foreach($result as $k=>$v){
            $time[$k] = $v['time'];
        }
        array_multisort($time,SORT_DESC,SORT_STRING, $result); // 按照时间倒序排序

        $jsonResult['total'] = count($result);
        $jsonResult['rows'] = array_slice($result, $offset, $pageSize);
        return json($jsonResult);
    }

    // 获取权限
    function getChmod($path){
        return substr(base_convert(@fileperms($path),10,8),-1);
//        return base_convert(@fileperms($path),10,8);
    }
}