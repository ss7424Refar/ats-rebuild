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
        $file = fopen(config('ats_pe_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix'),'r');

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
        $file = fopen(config('ats_pe_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix'),'r');

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
                        $jsonResult[] = $data[6];$jsonResult[] = $data[7];$jsonResult[] = $data[8];$jsonResult[] = $data[9];$jsonResult[] = $data[10];
                        $jsonResult[] = $data[11];$jsonResult[] = $data[12];$jsonResult[] = $data[3];$jsonResult[] = $data[0];
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
        // 反馈说不需要显示expired的数据，但是search form可以显示出来
        $map['status']  = ['<>', EXPIRED];
        $subMainQuery = Db::table('ats_task_basic')->where($map)->order('task_id desc')->limit($offset, $pageSize)->buildSql();
        $subStepsQuery = Db::table('ats_task_tool_steps')->distinct(true)->field('task_id as sid')->buildSql();

        $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid', 'LEFT')->order('task_id desc')->select();
        $total = Db::table('ats_task_basic')->where($map)->count();

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
        $searchTester = $this->request->param('searchTester');
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

        if (!empty($machineId)) {
            $map['machine_id'] = $machineId;
        }

        if (!empty($machineName)) {
            $map['machine_name'] = ['like', '%' . $machineName . '%'];
        }

        if (!empty($searchTester)) {
            if (!$this->hasRight){
                if ($searchTester == $this->loginUser) {
                    $map['tester'] = $this->loginUser;
                } else {
                    $map['tester'] = ''; // 当输入值为不为空, 没有权限不能让用户搜索到其他人的信息
                }
            } else {
                $map['tester'] = $searchTester;
            }
        } else {
            if (!$this->hasRight){
                $map['tester'] = $this->loginUser;
            }
        }

        if (!empty($tool)) {
            $map2['tool_name'] = ['in', $tool];
        }

        $subMainQuery = Db::table('ats_task_basic')->where($map)->buildSql();
        $subStepsQuery = Db::table('ats_task_tool_steps')->where($map2)->distinct(true)->field('task_id as sid')->buildSql();

        if (!empty($tool)) {
            $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid')->order('task_id desc')->limit($offset, $pageSize)->select();
            $total = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid')->count();
        } else {
            $result = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid', 'LEFT')->order('task_id desc')->limit($offset, $pageSize)->select();
            $total = Db::table($subMainQuery . ' a')->join([$subStepsQuery=> 'b'], 'a.task_id = b.sid',  'LEFT')->count();
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
     * edit modal task
     */
    public function editTask(){
        $form = stringSerializeToArray($this->request->param('formSerialize'));

        $atsTaskBasic = new AtsTaskBasic();

        // 修改器那里好像只是针对新增, 所以只能在这里再次判断一下
        if (stristr($form['machine_name'], 'Altair') !== false) {
            $form['category'] = 'In_House';
        } else {
            $form['category'] = 'ODM';
        }

        // 如果传入update的数据包含主键的话，可以无需使用where方法
        $atsTaskBasic->update($form);

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

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
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
            for ($j = 0; $j < count($outPut['task_steps']); $j++) {
                $jsonElement = json_decode($outPut['task_steps'][$j]['element_json']);
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

                    $outPut['task_steps'][$j]['element_json'] = json_encode($jsonElement);
                }
            }

            if (null != $outPut['task_steps']) {
                $fileName = config('ats_tasks_header'). $outPut['task_basic'][0]['shelf_switch'].
                    config('ats_file_underline'). $taskId. config('ats_file_suffix');
                $fileCreate = config('ats_temp_task_path'). $fileName;

                // 判断ats_temp_task_path是否存在, 因为.gitignore会把runtime下的内容ignore所以在此判断
                if (!is_dir(config('ats_temp_task_path'))) {
                    mkdir(config('ats_temp_task_path'), 0757);
                }
                if (file_exists($fileCreate)) {
                    unlink($fileCreate);
                }

                $file = fopen($fileCreate,"x+");
                file_put_contents($fileCreate, json_encode($outPut, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),FILE_APPEND);

                fclose($file);

                // unix
                Log::record('chmod 777 '. $fileName);
                chmod($fileCreate, 0777);

                Log::record('cp '. $fileName);
                // 如果目标文件已存在，将会被覆盖。
                $cpRes = copy($fileCreate, config('ats_pe_task'). $fileName);

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

                    $machine_name = Db::query('select machine_name from ats_task_basic where task_id = ? ', [$taskId]);
                    // result_path以机子名字开头, taskId结尾
                    $resultPath = ATS_RESULT_PATH. $machine_name[0]['machine_name']. config('ats_file_underline'). $taskId;

                    // update status for task
                    $atsTaskBasic = new AtsTaskBasic();
                    $atsTaskBasic->save([
                        'status'  => ONGOING,
                        'process' => 0,
                        'task_start_time' => $startTime,
                        'result_path' => $resultPath
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

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTaskInfoById(){
        $taskId = $this->request->param('taskId');
        $result = Db::table('ats_task_basic')->where('task_id', $taskId)->field('task_id, dmi_manufacturer, dmi_product_name, dmi_serial_number, dmi_part_number, dmi_oem_string, dmi_system_config, bios_ec')->select();
        return json_encode($result);
    }

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUpdateTaskInfoById(){
        $taskId = $this->request->param('taskId');
        $result = Db::table('ats_task_basic')->where('task_id', $taskId)->field('task_id, machine_id, machine_name, dmi_manufacturer, dmi_product_name, dmi_serial_number, dmi_part_number, dmi_oem_string, dmi_system_config, bios_ec, lan_ip, shelf_switch')->select();
        return json_encode($result);
    }

    /**
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSubTaskInfoById(){
        $taskId = $this->request->param('taskId');
        $pageSize = $this->request->param('pageSize');
        $pageNo = $this->request->param('pageNumber');
        $offset = ($pageNo-1)*$pageSize;

        $jsonResult = array();

        $result = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->order('steps')->field('tool_name, status, element_json')->limit($offset,$pageSize)->select();
        $total = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->count();

        if (!empty($result)){
            // 删除tool_type 键
            for ($i = 0; $i < count($result); $i++) {
                $tmpArray = json_decode($result[$i]['element_json'], true);  //得到的则是数组。
                unset($tmpArray['Tool_Type']);
                $result[$i]['element_json'] = json_encode($tmpArray, JSON_UNESCAPED_UNICODE); // 转成utf-8
            }
        }

        $jsonResult['total'] = $total;
        $jsonResult['rows'] = $result;

        return json_encode($jsonResult);
    }

    public function rerunTask() {
        $multiTask = json_decode($this->request->param('multiTask'));
        $result = array();
        // check if pending
        for ($i = 0; $i < count($multiTask); $i++) {
            $taskId = $multiTask[$i]->task_id;

            $sqlBasic = "insert into ats_task_basic (task_id, machine_id, machine_name, category, lan_ip, shelf_switch, ".
                    "    dmi_product_name, dmi_part_number, dmi_serial_number, dmi_oem_string, dmi_system_config, bios_ec,".
                    "    status, process, task_create_time, task_start_time, task_end_time, tester) ".
                    "SELECT NULL, machine_id, machine_name, category, lan_ip, shelf_switch, dmi_product_name, dmi_part_number,".
                    "    dmi_serial_number,dmi_oem_string, dmi_system_config, bios_ec, '" . PENDING . "', NULL, now(), NULL, NULL, '". $this->loginUser ."' ".
                    "FROM ats_task_basic where task_id = ? ";

            $sqlSteps = "insert into ats_task_tool_steps(".
                    "	task_id, tool_name, status, steps, element_json, ".
                    "    result_path, tool_create_time, tool_start_time, tool_end_time)".
                    "select ?, tool_name, '". PENDING ."', steps, element_json, NUll, now(),".
                    "	NULL, NULL from ats_task_tool_steps where task_id = ?";

            // 复制ats_task_basic表
            Db::execute($sqlBasic, [$taskId]);
            $newTaskId = Db::name('ats_task_basic')->getLastInsID();

            $total = Db::table('ats_task_tool_steps')->where('task_id', $taskId)->count();

            if (0 != $total) {
                Db::execute($sqlSteps, [$newTaskId, $taskId]);
            }
        }

        return "success";
    }

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUsers(){
        $query = $this->request->param('q');

        $jsonResult=array();

        $result = Db::table('users')->field('login')->select();
        if (empty(trim($query))) {
            for ($i = 0; $i < count($result); $i++) {
                $tmpArray = array('id' => $result[$i]['login'], 'text' => $result[$i]['login']);
                array_push($jsonResult, $tmpArray);
            }
        }else {
            for ($i = 0; $i < count($result); $i++) {
                if (stristr($result[$i]['login'], $query) !== false){
                    $tmpArray = array('id' => $result[$i]['login'], 'text' => $result[$i]['login']);
                    array_push($jsonResult, $tmpArray);
                }
            }
        }

        return json_encode($jsonResult);
    }

    /**
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function reverseTask() {
        $taskId = $this->request->param('taskId');

        $shelf_switch = Db::query('select shelf_switch from ats_task_basic where task_id = ? ', [$taskId]);
        $filePath = config('ats_pe_task'). config('ats_tasks_header'). $shelf_switch[0]['shelf_switch']. config('ats_file_underline'). $taskId. config('ats_file_suffix');

        if (file_exists($filePath)) {
            Log::record('reverse task = '. $taskId. ' fileName = '. $filePath);
            if (!unlink($filePath)) {
                Log::record('unlink fileName = '. $filePath . ' fail');
                return 'fail';
            } else {
                Log::record('unlink fileName = '. $filePath . ' success');
                // 更新表字段到pending状态 ats_basic
                Db::table('ats_task_basic')->where('task_id', $taskId)->where('status', '<>', PENDING)
                    ->update([
                       'status' => PENDING,
                        'process' => 0,
                        'task_start_time' => null,
                        'task_end_time' => null  // 好像可以不用更新finish_time, ongoing状态下finish time本身为null
                    ]);

                // 更新ats_steps pending状态, tool_create_time会被更新掉
                Db::table('ats_task_tool_steps')->where('task_id', $taskId)->where('status', '<>', PENDING)
                    ->update([
                        'status' => PENDING,
                        'tool_start_time' => null,
                        'tool_end_time' => null
                    ]);
                return 'done';
            }
        } else {
            Log::record( ' fileName = '. $filePath. ' not exist');
            return 'fail';
        }
    }
}