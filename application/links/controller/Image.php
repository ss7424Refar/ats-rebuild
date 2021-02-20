<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 20-12-31
 * Time: 上午9:59
 */

namespace app\links\controller;

use think\Log;
use think\Db;

class Image extends Common{
    public function getImageList() {

        $pageSize = $this->request->param('limit');
        $offset = $this->request->param('offset');
        $search = $this->request->param('search');

        $jsonResult = array();

        $res = Db::table('ats_bind_image_list')->where('bind_name', 'like', '%'.$search.'%')
            ->order('id', 'desc')->limit($offset, $pageSize)->select();

        $jsonResult['total'] = Db::table('ats_bind_image_list')->where('bind_name', 'like', '%'.$search.'%')
            ->count();
        $jsonResult['rows'] = $res;

        return json_encode($jsonResult);

    }

    public function getFileBindList() {
        $pageSize = $this->request->param('limit');
        $offset = $this->request->param('offset');
        $search = $this->request->param('search');

        $fileResult = $this->getFolderName(config('ats_pe_image'), $search);

        // 判断目录下的文件夹名是否在bind表中
        $dbResult = Db::table('ats_bind_image_list')->field('file_name')->select();
        $dbResult2 = array();

        foreach ($dbResult as $item) {
            array_push($dbResult2, $item['file_name']);
        }

        $fileResult2 = array();
        foreach ($fileResult as $item) {
            $isIn = in_array($item['file'], $dbResult2);

            if (!$isIn) {
                array_push($fileResult2, $item);
            }

        }

        $time = array();
        foreach($fileResult2 as $k=>$v){
            $time[$k] = $v['time'];
        }
        array_multisort($time,SORT_DESC,SORT_STRING, $fileResult2); // 按照时间倒序排序

        $jsonResult['total'] = count($fileResult2);
        $jsonResult['rows'] = array_slice($fileResult2, $offset, $pageSize);

        return json_encode($jsonResult);

    }

    public function bindImage() {
        $form = stringSerializeToArray($this->request->param('formSerialize'));

        $time = time();
        $form['id'] = date('Ymd', $time). $time;
        $form['create_time'] = Db::raw('now()');

        $form['bind_name'] = $form['tino']. config('ats_file_underline'). $form['machine']. config('ats_file_underline').
                $form['os']. config('ats_file_underline'). $form['region']. config('ats_file_underline').
                $form['phase'];


        Db::table('ats_bind_image_list')->insert($form);

        return "success";
    }

    public function getEditBindImageInfo() {
        $id = $this->request->param('id');

        $res = Db::table('ats_bind_image_list')->where('id', $id)->find();

        return json_encode($res);
    }

    public function editImage() {
        $form = stringSerializeToArray($this->request->param('formSerialize'));

        // 查找原来绑定的名称
        $r = Db::table('ats_bind_image_list')->where('file_name', $form['file_name'])->find();
        if (!empty($r)) {

            $form['bind_name'] = $form['tino']. config('ats_file_underline'). $form['machine']. config('ats_file_underline').
                $form['os']. config('ats_file_underline'). $form['region']. config('ats_file_underline').
                $form['phase'];

            Db::table('ats_bind_image_list')->where('id', $form['id'])->update($form);

            // 更新steps中的关联case (qiu lin那边的逻辑应该是只有Test_Image用Test_Image，有Key_Image用Key_Image)
            if (config('is_read_from_db')) {
                $res = Db::table('ats_task_tool_steps')
                    ->where('element_json', 'like', '%'. $r['bind_name'] .'%')->select();

                foreach ($res as $item) {
                    $json = json_decode($item['element_json']);
                    // 这里判断的是Key_Image & Test_Image 因为存在以前的Test_Image，即便绑定信息改了也不会影响.
                    if (array_key_exists('Key_Image', $json) && array_key_exists('Test_Image', $json)) {

                        if ($form['file_name'] == $json->Key_Image && $r['bind_name'] == $json->Test_Image) {

                            $json->Test_Image = $form['bind_name'];

                            Db::table('ats_task_tool_steps')->where('task_id', $item['task_id'])
                                ->where('steps', $item['steps'])->update(['element_json' => json_encode($json)]);

                            Log::record('[Image][editImage][TaskId][Step] '. $item['task_id']. '_' . $item['steps']);
                            Log::record('[Image][editImage][Update] '. $r['bind_name']. ' ==> '. $form['bind_name']);
                        }
                    }
                }

            }

        }

        return "success";
    }

    public function deleteImage() {
        $id = $this->request->param('id');

        $res = Db::table('ats_bind_image_list')->where('id', $id)->find();

        $existArr = array();
        if (!empty($res)) {
            // 判断是否有case存在
            if (config('is_read_from_db')) {
                $r = Db::table('ats_task_tool_steps')->field('task_id, steps, element_json')
                    ->whereIn('status', [ONGOING, PENDING])->select();

                foreach ($r as $item) {
                    $json = json_decode($item['element_json']);
                    if (array_key_exists('Key_Image', $json) || array_key_exists('Test_Image', $json)) {
                        // 如果是只有Test_Image, 因为镜像会删除. 所以会受影响.
                        if (!array_key_exists('Key_Image', $json)) {
                            if ($res['file_name'] == $json->Test_Image) {
                                $existArr[] = $item['task_id'] .'(' . $item['steps']. ')';
                            }
                            // 有Key_Image和Test_Image
                        } else {
                            if ($res['file_name'] == $json->Key_Image && $res['bind_name'] == $json->Test_Image) {
                                $existArr[] = $item['task_id'] .'(' . $item['steps']. ')';
                            }
                        }

                    }

                }

            } else {
                Db::table('ats_bind_image_list')->where('id', $id)->delete();

                return json_encode(array('code'=>'ok', 'data'=>null));
            }

        }

        if (!empty($existArr)) {
            return json_encode(array('code'=>'ng', 'data'=>$existArr));
        } else {
            Db::table('ats_bind_image_list')->where('id', $id)->delete();
            // 删除image下的文件夹
            $filePath = config('ats_pe_image'). $res['file_name'];

            if (file_exists($filePath)) {
                Log::record('[Image][deleteImage][id][file_name] '. $res['id']. '__'. $res['file_name']);
                $cmd = 'rm -r '. $filePath;
                Log::record('[Image][deleteImage] '. $cmd);
                system($cmd, $argv);
                return json_encode(array('code'=>'ok2', 'data'=>$argv));
            }

            return json_encode(array('code'=>'ok2', 'data'=>'no file'));
        }
    }

    public function unbindImage() {
        $id = $this->request->param('id');
        Db::table('ats_bind_image_list')->where('id', $id)->delete();

        return "success";
    }

    private function getFolderName($path, $search) {

        $handler = opendir($path);

        $result = array();

        while (($filename = readdir($handler)) !== false) {
            //略过linux目录的名字为'.'和‘..'的文件
            if ($filename != "." && $filename != "..") {

                $p = $path.DIRECTORY_SEPARATOR.$filename;
                $dirFlag = is_dir($p);

                if ($dirFlag) {
                    if (empty(trim($search))) {
                        $tmpArray = array(
                            'file'=>$filename,
                            'time'=>date("Y-m-d H:i:s",filemtime($p)));
                        array_push($result, $tmpArray);
                    } else {
                        if (stristr($filename, $search) !== false) {
                            $tmpArray = array(
                                'file'=>$filename,
                                'time'=>date("Y-m-d H:i:s",filemtime($p)));
                            array_push($result, $tmpArray);
                        }
                    }
                }
            }
        }

        return $result;
    }

}