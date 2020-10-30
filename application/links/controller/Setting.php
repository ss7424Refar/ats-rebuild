<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 20-10-13
 * Time: 下午3:09
 */

namespace app\links\controller;

use think\Log;
use think\Db;

class Setting extends Common{
    public function configList() {

        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $jsonResult = array();

        $res = Db::table('ats_common_tool_config')->where('user', $this->loginUser)
                ->order('add_time', 'desc')->limit($offset, $pageSize)->select();

        $jsonResult['total'] = Db::table('ats_common_tool_config')
                ->where('user', $this->loginUser)->count();
        $jsonResult['rows'] = $res;

        return json_encode($jsonResult);

    }

    public function addConfigList() {
        $remark = $this->request->param('remark');
        $formObj = $this->request->param('formObj');
        $createTime = date("YmdHis");

        $fileName = config('ats_config_name').$createTime;

        Db::table('ats_common_tool_config')
            ->insert([
                'name'    => $fileName,
                'detail'  => $formObj,
                'user'    => $this->loginUser,
                'add_time'=> Db::raw('now()'),
                'remark'  => $remark
            ]);

        // 生成文件
        $this->writeJson2Ini($formObj, $fileName);

        return "done";
    }

    public function deleteConfig() {
        $name = $this->request->param('name');

        Db::table('ats_common_tool_config')
            ->where('name', $name)->delete();

        // 删除文件
        $fileCreate = config('ats_config_list'). $name. '.ini';
        if (file_exists($fileCreate)) {
            unlink($fileCreate);
        }

        return 'done';
    }

    public function getNodeFile() {

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

            // 链接ftp
            $conn = ftp_connect(config('ftp_address'), config('ftp_port'));

            // 登录
            ftp_login($conn, config('ftp_user'), config('ftp_password'));

            $here = ftp_pwd($conn);

            //被动模式（PASV）的开关，打开或关闭PASV（1表示开）
            ftp_pasv($conn, 1);

            // 你想上传 "abc.txt"这个文件，上传后命名为"xyz.txt
            ftp_put($conn, $file['name'], $filename, FTP_ASCII);

            ftp_quit($conn);

            $cmd = 'rm -rf ' . $filename .' 2>&1';
            exec($cmd, $out, $return_val);

            Log::record('[rm -rf] '. $cmd. ' [Info] '. json_encode($out));

            return json(array('code'=>200, 'msg'=>'Upload Success'));
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

    private function writeJson2Ini($formObj, $fileName) {

        $fileCreate = config('ats_config_list').$fileName. '.ini';

        if (file_exists($fileCreate)) {
            unlink($fileCreate);
        }
        $f = fopen($fileCreate,"x+");

        $content = '';
        $formObj = json_decode($formObj, true);
        foreach ($formObj as $item) {
            $content = $content . '[' . $item['Step'] . ']'. PHP_EOL;
            foreach ($item as $key => $value) {
                if ('Step' != $key) {
                    $content = $content .$key. '=' . $value. PHP_EOL;
                }
            }
            $content = $content. PHP_EOL;
        }

        Log::record('[ writeJson2Ini ]' . PHP_EOL. $content);

        file_put_contents($fileCreate, $content);

        fclose($f);
    }
}