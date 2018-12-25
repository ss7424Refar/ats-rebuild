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