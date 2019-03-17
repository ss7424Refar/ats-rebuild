<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-16
 * Time: 上午9:50
 */

namespace ext;

use think\Log;

class FTPUtil
{

    var $connector;
    var $login_result;

    //连接FTP
    function __construct($ftp_server, $uname, $passwd)
    {
        $this->connector = ftp_connect($ftp_server);
        if (!$this->connector) {
            Log::record("FTP connection failed!");
            die;
        }

        $this->login_result = ftp_login($this->connector, "$uname", "$passwd");
        if (!$this->login_result) {
            Log::record("FTP login failed!");
            die;
        } else {
            Log::record("FTP login success!");
        }
    }
    //  获取最后修改时间, 如果成功返回一个 UNIX 时间戳，否则返回 -1
    function last_modtime($value)
    {
        return ftp_mdtm($this->connector, $value);
    }

    // 更改当前目录
    function change_dir($target_dir)
    {
        return ftp_chdir($this->connector, $target_dir);
    }

    // 获取当前目录
    function get_dir()
    {
        return ftp_pwd($this->connector);
    }

    // 获取文件列表
    function get_file_list($directory)
    {
        return ftp_nlist($this->connector, $directory);
    }

    // 如果成功，该函数返回 TRUE。如果失败，则返回 FALSE。上传文件
    function up_file($path, $newpath)
    {

        return ftp_put($this->connector, $newpath, $path,FTP_BINARY);

    }

    function down_file($path, $newpath) {
        return ftp_get($this->connector, $newpath, $path,FTP_BINARY);
    }
    function mode($pasvmode)
    {
        ftp_pasv($this->connector, $pasvmode);
    }

    //退出
    function ftp_bye()
    {
        ftp_quit($this->connector);
    }

}