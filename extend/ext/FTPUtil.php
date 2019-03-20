<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-3-16
 * Time: 上午9:50
 */

namespace ext;

use think\Log;
use ext\MailerUtil;

class FTPUtil
{

    var $connector;
    var $login_result;

    //连接FTP
    function __construct($ftp_server, $uname, $passwd)
    {
        $this->connector = ftp_connect($ftp_server);
        // connect
        if (!$this->connector) {
            Log::record("FTP connection failed!");

            $status = file_get_contents(config('ats_local_test_pc'). 'ftp_mail.txt');

            if ('not send' == $status) {
                MailerUtil::send(config('ftp_notice'), null, 'FTP  connection failed!', ' connection failed!');
                file_put_contents(config('ats_local_test_pc'). 'ftp_mail.txt', 'send');
            }
            die;
        } else {
            file_put_contents(config('ats_local_test_pc'). 'ftp_mail.txt', 'not send');
        }

        // login
        $this->login_result = ftp_login($this->connector, "$uname", "$passwd");
        if (!$this->login_result) {
            Log::record("FTP login failed!");
            die;
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

    /**
     * @param $path
     * @param $newpath
     * @return bool
     * path 为ftp上的路径， new path为本地路径
     */
    function down_file($path, $newpath) {
        return ftp_get($this->connector, $newpath, $path,FTP_BINARY);
    }
    function mode($pasvmode)
    {
        ftp_pasv($this->connector, $pasvmode);
    }

    /**
     * @param $localFile
     * @param $ftpFile
     * @return bool
     * 最后的修改时间相同则为false，不相同为true
     *
     */
    function check_is_update($ftpFile) {

        $ftpFileTime = $this->last_modtime($ftpFile);

        if ($ftpFileTime != -1) {
            Log::record("[checkUpdate] FTP's TestStatus file last modify time is : ". date ("F d Y H:i:s.", $ftpFileTime));
            Log::record('[checkUpdate] [ftpTime]:'. $ftpFileTime);

            $saveUnix = file_get_contents(config('ats_local_test_pc'). 'unix_time.txt');
            if ('' == $saveUnix) {
                file_put_contents(config('ats_local_test_pc'). 'unix_time.txt', $ftpFileTime);
                return true;
            } else {
                if ($ftpFileTime != $saveUnix) {
                    file_put_contents(config('ats_local_test_pc'). 'unix_time.txt', $ftpFileTime);
                    return true;
                }
            }
        } else {
            Log::record("[checkUpdate] get FTP's TestStatus fail");
            return false;
        }
    }

    //退出
    function ftp_bye()
    {
        ftp_quit($this->connector);
    }

}