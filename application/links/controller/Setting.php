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