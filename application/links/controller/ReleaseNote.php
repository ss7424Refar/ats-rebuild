<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-6-9
 * Time: 下午12:43
 */


namespace app\links\controller;

class ReleaseNote extends Common
{
    public function getReleaseNotes() {

        $item = $this->request->param('item');

        $filename = null;
        if ('ui' == $item) {
            $filename = ROOT_PATH. 'public/ReleaseNote_UI.md';
        } else {
            $filename = ROOT_PATH. 'public/ReleaseNote_PE.md';
        }

        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'

        //通过file size获得文件大小，将整个文件一下子读到一个字符串中
        $contents = fread($handle, filesize ($filename));
        fclose($handle);

        return $contents;
    }

}