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
                    $tmpArray = array('id' => $data[2], 'text' => $appendedTestMachine);
                    array_push($jsonResult, $tmpArray);
                }else {
                    if (stristr($appendedTestMachine, $query) !== false){
                        $tmpArray = array('id' => $data[2], 'text' => $appendedTestMachine);
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

    /* @throws
     * task data page init
     */
    public function taskPagination(){
        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $jsonResult = array();
        $map = array();
        if (!$this->hasRight){
            $map['tester'] = $this->loginUser;
        }

        $subMainQuery = Db::table('ats_task_basic')->where($map)->order('task_id desc')->limit($offset, $pageSize)->buildSql();
        $subStepsQuery = Db::table('ats_task_tool_steps')->distinct(true)->field('task_id as sid')->buildSql();

        $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid', 'LEFT')->order('task_id desc')->select();
        $total = Db::table('ats_task_basic')->count();

        $jsonResult['total'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);
    }
    /* @throws
     * task data search page init
     */
    public function taskSearchPagination(){
        $taskId = $this->request->param('taskId');
        $machineId = $this->request->param('machineId');
        $machineName = $this->request->param('machineName');
        $serialNo = $this->request->param('serialNo');
        $createTime = $this->request->param('createTime');
        $finishTime = $this->request->param('finishTime');
        $tool = json_decode($this->request->param('tool'));
        $status = $this->request->param('status');

        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $map = array(); // basic查询条件
        $map2 = array(); // steps查询条件

        if (!empty($taskId)) {
            $map['task_id'] = $taskId;
            $map2['task_id'] = $taskId;
        }

        if (!empty($createTime)) {
            $map['task_create_time'] = ['>= time', $createTime]; // 自动识别列datetime的属性
//            $map2['tool_start_time'] = ['>= time', $startTime];
        }
        // task的finishTime比steps更新要晚，所以map2没有tool_end_time
        // exp查询的条件不会被当成字符串，所以后面的查询条件可以使用任何SQL支持的语法，包括使用函数和字段名称。
        if (!empty($finishTime)) {
            $map['task_end_time'] = [['<= time', $finishTime. ' 23:59:59'], ['exp', Db::raw('is null')], 'or'];
        }

        if ('all' != $status) {
            if (FINISHED == $status) {
                $map['status'] = ['in', [PASS, FAIL]];
            } else {
                $map['status'] = $status;
            }
        }

        if (!$this->hasRight){
            $map['tester'] = $this->loginUser;
        }

        if (!empty($machineId)) {
            $map['machine_id'] = $machineId;
        }

        if (!empty($machineName)) {
            $map['machine_name'] = ['like', '%' . $machineName . '%'];
        }

        if (!empty($serialNo)) {
            $map['dmi_serial_number'] = $serialNo;
        }

        if (!empty($tool)) {
            $map2['tool_name'] = ['in', $tool];
        }

        $subMainQuery = Db::table('ats_task_basic')->where($map)->buildSql();
        $subStepsQuery = Db::table('ats_task_tool_steps')->where($map2)->distinct(true)->field('task_id as sid')->buildSql();

        $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid')->order('task_id desc')->limit($offset, $pageSize)->select();
        $total = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid')->order('task_id desc')->limit($offset, $pageSize)->count();

        $jsonResult['total'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);
    }

    /*
     * add modal task
     */
    public function addTask(){
        $form = stringSerializeToArray($this->request->param('formSerialize'));

        // 修改转换machine_id至machine_name
        // machine_name至machine_id
        $machine_name = str_replace($form['machine_name'], '', $form['machine_id']);
        $machine_name = str_replace('()', '', $machine_name);

        $form['machine_id'] = $form['machine_name'];
        $form['machine_name'] = $machine_name;

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
    /* @throws
     * assign pending Task
     */
    public function assignTask() {
        $multiTask = json_decode($this->request->param('multiTask'));

        for ($i = 0; $i < count($multiTask); $i++) {
            $taskId = $multiTask[$i]->task_id;

            $outPut = array();

            $outPut['task_basic'] = AtsTaskBasic::where('task_id', $taskId)->select();
            $outPut['task_steps'] = AtsTaskToolSteps::where('task_id', $taskId)->select();

            // 给31用的需要转换Execute_Job
            for ($i = 0; $i < count($outPut['task_steps']); $i++) {
                $jsonElement = json_decode($outPut['task_steps'][$i]['element_json']);
                if (JUMP_START == $jsonElement->Tool_Type) {
                    if ('Fast Startup,Standby,Microsoft Edge' == $jsonElement->Execute_Job){
                        $jsonElement->Execute_Job = 1;
                    } elseif ('Fast Startup' == $jsonElement->Execute_Job){
                        $jsonElement->Execute_Job = 2;
                    } elseif ('BatteryLife' == $jsonElement->Execute_Job){
                        $jsonElement->Execute_Job = 5;
                    } elseif('Fast Startup,Standby,Microsoft Edge,BatteryLife,DataGrab' == $jsonElement->Execute_Job) {
                        $jsonElement->Execute_Job = 6;
                    }

                    $outPut['task_steps'][$i]['element_json'] = json_encode($jsonElement);
                }
            }

            if (null != $outPut['task_steps']) {
                $fileName = ATS_TMP_TASKS_HEADER. $outPut['task_basic'][0]['shelf_switch'].
                                ATS_FILE_UNDERLINE. $taskId. ATS_FILE_suffix;
                $fileCreate = ATS_TMP_TASKS_PATH. $fileName;

                $file = fopen($fileCreate,"x+");
                file_put_contents($fileCreate, json_encode($outPut, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

                fclose($file);

                // unix
                Log::record('chmod 777 '. $fileName);
                chmod($fileCreate, 0777);

                Log::record('cp '. $fileName);
                $cpRes = copy($fileCreate, ATS_TASKS_PATH. $fileName);

                Log::record('rm '. $fileName);
                $rmRes = unlink($fileCreate);

                if(1 != $cpRes){
                    Log::record('copy fail '. $fileName);
                    exit();
                }else if (1 != $rmRes){
                    Log::record('remove fail '. $fileName);
                    exit();
                } else {

                    $startTime = date("Y-m-d H:i:s");

                    // update status for task
                    $atsTaskBasic = new AtsTaskBasic();

                    $atsTaskBasic->save([
                        'status'  => ONGOING,
                        'process' => 0,
                        'task_start_time' => $startTime
                    ],['task_id' => $taskId]);

                    $atsTaskToolSteps = new AtsTaskToolSteps();
                    $atsTaskToolSteps->save([
                        'status'  => ONGOING,
                        'tool_start_time' => $startTime
                    ], ['task_id' => $taskId, 'steps' => 1]);
                }
            }


        }
        return "done";
    }

    /* @throws
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

    /* @throws
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