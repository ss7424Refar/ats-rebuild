<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-6-9
 * Time: 下午12:43
 */


namespace app\index\controller;

class ReleaseNote extends Common
{
    public function getReleaseNotes() {

        $filename = ROOT_PATH. 'public/ReleaseNote.md';
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'

        //通过file size获得文件大小，将整个文件一下子读到一个字符串中
        $contents = fread($handle, filesize ($filename));
        fclose($handle);

        return $contents;
    }

}