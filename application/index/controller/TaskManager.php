<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-20
 * Time: 上午10:41
 */

namespace app\index\controller;

use app\index\model\AtsTaskBasic;
use think\Db;

class TaskManager extends Common{
    /*
     * read machine id and name
     */
    public function readMachineInfo(){
        $query = $this->request->param('q');
        $file = fopen(ATS_PREPARE_PATH. ATS_PREPARE_FILE. ATS_FILE_suffix,'r');

        $jsonResult=array();
        $line=0;
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $line++;
            if ($line>=2){
                $machineId=$data[2];
                $appendedTestMachine=$data[1]. "(". $machineId. ")" ;
                if (empty(trim($query))) {
                    $tmpArray = array('id' => $data[1], 'text' => $appendedTestMachine);
                    array_push($jsonResult, $tmpArray);
                }else {
                    if (stristr($appendedTestMachine, $query) !== false){
                        $tmpArray = array('id' => $data[1], 'text' => $appendedTestMachine);
                        array_push($jsonResult, $tmpArray);
                    }

                }

            }

        }
        echo json_encode($jsonResult);

        fclose($file);

    }
    /*
     * read machine info
     */
    public function readTestPCDetail(){
        $file = fopen(ATS_PREPARE_PATH. ATS_PREPARE_FILE. ATS_FILE_suffix,'r');

        $machineId = $this->request->param('machineId');

        $jsonResult=array();
        $line=0;

        if(!empty($machineId)){
            while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
                $line++;
                if ($line>=2){
                    if ($data[2] == $machineId){
//                        $tmpArray = array('product'=> $data[6], 'sn' => $data[7], 'pn' => $data[8], 'oem' => $data[9], 'sys'=> $data[10], 'lanIp' => $data[3], 'shelfId' => $data[0], 'bios'=>$data[11]);
//                        array_push($jsonResult, $tmpArray);
                        $jsonResult[] = $data[6];$jsonResult[] = $data[7];$jsonResult[] = $data[8];$jsonResult[] = $data[9];
                        $jsonResult[] = $data[10];$jsonResult[] = $data[11];$jsonResult[] = $data[3];$jsonResult[] = $data[0];
                        break;
                    }
                }

            }

        }
        echo json_encode($jsonResult);
        fclose($file);
    }

    /*
     * task data page init
     */
    public function taskPagination(){
        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $jsonResult = array();
        if ($this->hasRight){

            $subMainQuery = Db::table('ats_task_basic')->order('task_id desc')->limit($offset,$pageSize)->buildSql();
            $subStepsQuery = Db::table('ats_task_tool_steps')->distinct(true)->field('task_id as sid')->buildSql();

            $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid', 'LEFT')->order('task_id desc')->select();
            $total = Db::table('ats_task_basic')->count();
        } else {
            $subMainQuery = Db::table('ats_task_basic')->where('tester', $this->loginUser)->order('task_id desc')->limit($offset,$pageSize)->buildSql();
            $subStepsQuery = Db::table('ats_task_tool_steps')->distinct(true)->field('task_id as sid')->buildSql();

            $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid', 'LEFT')->order('task_id desc')->select();
            $total = Db::table('ats_task_basic')->where('tester', $this->loginUser)->count();
        }

        $jsonResult['total'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);
    }

    /*
     * add modal task
     */
    public function addTask(){
        $form = stringSerializeToArray($this->request->param('formSerialize'));
        $atsTaskBasic = new AtsTaskBasic();

        $atsTaskBasic->data($form);
        $atsTaskBasic->save();

        return "success";
    }

    /*
    * page init for tool steps modal
    */
    public function stepsPagination(){
        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $taskId = $this->request->param('taskId');

        $jsonResult = array();

        $result = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->order('steps')->limit($offset,$pageSize)->select();
        $total = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->count();

        $jsonResult['total'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);
    }

    /*
     * check the task should which op tool
     */
    public function checkTool(){
        $taskId = $this->request->param('taskId');
        $total = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->count();

        return $total;
    }

    public function getTaskInfoById(){
        $taskId = $this->request->param('taskId');
        $result = Db::table('ats_task_basic')->where('task_id', $taskId)->field('task_id, dmi_product_name, dmi_serial_number, dmi_part_number, dmi_oem_string, dmi_system_config, bios_ec')->select();
        return json_encode($result);
    }

}