<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-9-20
 * Time: 上午10:41
 */

namespace app\index\controller;

use app\index\model\AtsTaskBasic;
use app\index\model\AtsTaskToolSteps;
use think\Db;
use think\Log;

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
     * delete pending task
     */
    public function deleteTask() {
        $multiTask = json_decode($this->request->param('multiTask'));
        $result = array();
        // check if pending
        for ($i = 0; $i < count($multiTask); $i++) {
            $taskId = $multiTask[$i]->task_id;
            $status = AtsTaskBasic::where('task_id', $taskId)->column('status');
            if (PENDING == $status[0]) {
                // steps
                AtsTaskToolSteps::destroy($taskId);
                // basic
                AtsTaskBasic::destroy($taskId);
            }
        }

        return "success";
    }
    /*
     * assign pending Task
     */
    public function assignTask() {
        $multiTask = json_decode($this->request->param('multiTask'));

        $filePath = ATS_TMP_TASKS_PATH;
//        $fileName = ATS_TMP_TASKS_HEADER. $row['ShelfID']. ATS_FILE_UNDERLINE. $row['SwitchId'].
//                    ATS_FILE_UNDERLINE. $multiTask[$i]['TaskID']. ATS_FILE_suffix;
        for ($i = 0; $i < count($multiTask); $i++) {
            $taskId = $multiTask[$i]->task_id;

            $outPut = array();
            $outPut['task_basic'] = AtsTaskBasic::where('task_id', $taskId)->select();
            $outPut['task_steps'] = AtsTaskToolSteps::where('task_id', $taskId)->select();

            Log::record(json_encode($outPut, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

            if (null != $outPut['task_steps']) {
            $filePath = ATS_TMP_TASKS_PATH;
            $fileName = ATS_TMP_TASKS_HEADER. $row['ShelfID']. ATS_FILE_UNDERLINE. $row['SwitchId'].
                ATS_FILE_UNDERLINE. $multiTask[$i]['TaskID']. ATS_FILE_suffix;
            $fileCreate = ATS_TMP_TASKS_PATH. ATS_TMP_TASKS_HEADER. $row['ShelfID']. ATS_FILE_UNDERLINE. $row['SwitchId'].
                ATS_FILE_UNDERLINE. $multiTask[$i]['TaskID']. ATS_FILE_suffix;;

            }

//            if (PENDING == $res['']) {
//                // steps
//                AtsTaskToolSteps::destroy($taskId);
//                // basic
//                AtsTaskBasic::destroy($taskId);
//            }


//            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//                $filePath = ATS_TMP_TASKS_PATH;
//                $fileName = ATS_TMP_TASKS_HEADER. $row['ShelfID']. ATS_FILE_UNDERLINE. $row['SwitchId'].
//                    ATS_FILE_UNDERLINE. $multiTask[$i]['TaskID']. ATS_FILE_suffix;
//                $fileCreate = $filePath. $fileName;
//                $file = fopen($fileCreate,"x+");
//                foreach ($row as $key=>$value){
//                    if(null == $value){
//                        $value='NULL';
//                    }
//                    if ('Fast Startup,Standby,Microsoft Edge' == $value){
//                        $value = 1;
//                    } elseif ('Fast Startup' == $value){
//                        $value = 2;
//                    } elseif ('BatteryLife' == $value){
//                        $value = 5;
//                    } elseif('Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab' == $value) {
//                        $value = 6;
//                    }
//
//                    $str = $key. '='. $value.PHP_EOL;
//                    file_put_contents($fileCreate, $str,FILE_APPEND);
//
//                }
//                fclose($file);
//                // unix
//                chmod($fileCreate, 0777);
//                $cpRe = copy($fileCreate, ATS_TASKS_PATH. $fileName);
//                chmod(ATS_TASKS_PATH. $fileName, 0777);
//                $unRe = unlink($fileCreate);
//
//                if(1 != $cpRe){
//                    echo json_encode($multiTask[$i]['TaskID']. " copy fail". $cpRe);
//                    exit();
//                }else if (1 != $unRe){
//                    echo json_encode($multiTask[$i]['TaskID']. "delete fail". $unRe);
//                    exit();
//                }else {
//                    // update
//                    $stmtUpdate = $pdoc->prepare($sql4TestTask);
//                    $stmtUpdate->bindParam(1, $multiTask[$i]['TaskID']);
//                    $stmtUpdate->execute();
//                }
//            }
        }

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