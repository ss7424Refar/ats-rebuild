<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-7-21
 * Time: 下午3:15
 */

namespace app\services\controller;

use think\Controller;
use think\Db;
use PHPExcel; // 导出
use PHPExcel_Style_Fill;
use ext\MailerUtil;
use think\Exception;

header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type,multipart/form-data'); // 这个要设置否则bs-table无法加载

/*
 * interface for ats
 */
class MachineSever extends Controller {
    private $statusArray = array('在库', '待借出审批', '审核通过', '使用中', '待报废审批', '报废', '待删除审批');
    private $sectionArray = array('1884'=>'SCD', '2271'=>'SWV', '2272'=>'PSD', '2273'=>'CUD', '2274'=>'FWD',
                                    '442'=>'SYD', '462'=>'HWD', '485'=>'MED', '491'=>'CSV', '499'=>'HWV',
                                    '520'=>'PAV','540'=>'SSD');
    private $departArray = array('29'=>'DT部', '33'=>'VT部', '37'=>'SWT部');

    public function getMachineList() {

        $pageSize = $this->request->param('limit');
        $offset = $this->request->param('offset');

        $userId = $this->request->param('userId');
        $formData = $this->request->param('formData');

        $map = $this->getSearchCondition($formData);

        $jsonRes = array();

//        $res = Db::query('select fixed_no, MODEL_NAME, model_status, user_name, department, section_manager,
//                                start_date, predict_date from itd.d_main_engine order by instore_date desc limit ?, ?', [$offset, $pageSize]);

        $res = Db::table('itd.d_main_engine')->where($map)->order('instore_date desc')->limit($offset, $pageSize)->select();
        for ($i = 0; $i < count($res); $i++) {

            // 判断是否要有取消申请
            if (1 == $res[$i]['model_status'] && $userId == $res[$i]['user_id'] ) {
                $res[$i]['op'] = 'apply';
            } else {
                $res[$i]['op'] = 'no_apply';
            }

            $res[$i]['model_status'] = $this->statusArray[$res[$i]['model_status']];
            $res[$i]['department'] = $this->departArray[$res[$i]['department']];
            $res[$i]['section_manager'] = $this->sectionArray[$res[$i]['section_manager']];
        }

//        $res2 = Db::query('select count(*) as total from itd.d_main_engine');
        $total = Db::table('itd.d_main_engine')->where($map)->count();

        $jsonRes['total'] = $total;
        $jsonRes['rows'] = $res;
        return json($jsonRes);
    }

    public function outputExcel() {

        $formData = $this->request->param('formData');
        $map = $this->getSearchCondition($formData);

        $letter = range('A','J');//列坐标
        $header= ['No', '资产编号', '资产名称', 'CPU', '硬盘', '内存', '部门', '课', '状态', '用户名']; //表头,名称可自定义
        $field = array('fixed_no', 'MODEL_NAME', 'CPU', 'HDD', 'MEMORY', 'department', 'section_manager', 'model_status', 'user_name');

        ini_set('memory_limit','500M');
        // php 当数据大起来的时候，Db::table的select()会报错..其实是内存不足的原因. 所以暂时导出几个关键的字段.
        $list = Db::table('itd.d_main_engine')->where($map)->
                    field(implode(',', $field))
                    ->order('fixed_no desc')->select();

        $objPHPExcel = new PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);

        //设置A列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // 自动调节列宽
        for ($i=0;$i<count($letter);$i++) {
            $objPHPExcel->setActiveSheetIndex(0)-> getColumnDimension($letter[$i])->setAutoSize(true);
        }
        //生成表头
        for($i=0;$i<count($letter);$i++)
        {
            //设置表头值
            $objPHPExcel->getActiveSheet()->setCellValue("$letter[$i]1",$header[$i]);
            //设置表头字体样式
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setName('宋体');
            //设置表头字体大小
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(11);
            //设置表头字体是否加粗
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
            //设置表头文字水平居中
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置文字上下居中
            $objPHPExcel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置单元格背景色
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FF6DBA43');
            //设置字体颜色
            $objPHPExcel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->getColor()->setARGB('FFFFFFFF');
        }

        //6.循环刚取出来的数组，将数据逐一添加到excel表格。
        for($i=0; $i<count($list); $i++){

            $list[$i]['model_status'] = $this->statusArray[$list[$i]['model_status']];
            $list[$i]['department'] = $this->departArray[$list[$i]['department']];
            $list[$i]['section_manager'] = $this->sectionArray[$list[$i]['section_manager']];

            for ($j=0;$j<count($letter);$j++) {
                if (0 == $j) {
                    $objPHPExcel->getActiveSheet()->setCellValue($letter[$j].($i+2), $i+1); //No
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue($letter[$j].($i+2), $list[$i][$field[$j-1]]); // 其他数据
                }
            }

        }
        //7.设置保存的Excel表格名称
        $filename = 'machine_info_'.time().'.xls';
        //8.设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle('output');

        // Redirect output to a client’s web browser (Excel5)
        ob_end_clean();//清除缓冲区,避免乱码

        //9.设置浏览器窗口下载表格
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        //生成excel文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //下载文件在浏览器窗口
        $objWriter->save('php://output');
    }

    public function loginCheck() {
        $user = $this->request->param('user');
        $password = $this->request->param('password');

        // 确认用户是否存在
        $info1 = Db::table('itd.m_user')->where('USER_ID', $user)->select();
        if (empty($info1)) {
            // 代表用户不存在
            $tmp['code'] = '101';
            $tmp['data'] = null;
            return json($tmp);
        } else {
            $info2 = Db::table('itd.m_user')->where('USER_ID', $user)->where('password', $password)->field('user_name')->select();
            if (empty($info2)) {
                $tmp['code'] = '201';
                $tmp['data'] = null;
                return json($tmp);// 代表password不对
            } else {
                $tmp['code'] = '301';
                $tmp['data'] = $info2[0]['user_name'];
                return json($tmp);
            }
        }
    }

    public function apply() {
        $userId = $this->request->param('userId');
        $who = Db::table('itd.m_user')->where('USER_ID', $userId)->field('user_name, mail')->find();

        $user = $who['user_name'];
        $from = $who['mail'];

        $selection = $this->request->param('selection'); $selection = json_decode($selection); // 转为数组

        $sectionArray = array();

        for ($i = 0; $i < count($selection); $i++) {

            $fixed_no = $selection[$i]->fixed_no;

            $query = Db::table('itd.d_main_engine')->where('fixed_no', $fixed_no)
                        ->where('model_status', 0)->where('user_id', null)->select();
            if (!empty($query)) {
                // 更新状态
                Db::table('itd.d_main_engine')->where('fixed_no', $fixed_no)
                    ->where('model_status', 0)->where('user_id', null)
                    ->update([
                        'user_name'    => $user,
                        'user_id'      => $userId,
                        'model_status' => '1',
                        'start_date' => Db::raw('now()')
                    ]);

                // 取得课
                if (!in_array($query[0]['section_manager'], $sectionArray)) {
                    array_push($sectionArray, $query[0]['section_manager']);
                }

            }
        }

        // 发送邮件
        if (!empty($sectionArray)) {
            for ($i = 0; $i < count($sectionArray); $i++) {
                $res = Db::query('select a.mail from itd.m_user a, itd.r_role_user b 
                                        where a.id = b.tech_id and b.role_id = 5 and a.section = ?', [$sectionArray[$i]]);

                // 转成一维数组
                $tos = array();
                for ($j = 0; $j < count($res); $j++) {
                    array_push($tos, $res[$j]['mail']);
                }

                $subject = 'Workflow:现有样机需借出审批('.date('Y-m-d H:i:s', time()). ' '. $user. ')';
//                echo $this->getMailTemplate($user, $subject); // 需要填写subject
                MailerUtil::send2($from, $tos, null, $subject, $this->getMailTemplate($user, $subject));
            }
            echo 'done';
        }
    }

    public function cancel() {
        $fixed_no = $this->request->param('no');
        $userId = $this->request->param('userId');

        $res = Db::table('itd.d_main_engine')->where('fixed_no', $fixed_no)
            ->where('model_status', 1)->where('user_id', $userId)
            ->update([
                'user_name'    => null,
                'user_id'      => null,
                'model_status' => '0',
                'start_date' => null
            ]);
        if ($res > 0) {
            return 'done'; // 如果更新了则返回done
        }
        return 'no';
    }

    public function input() {
        // 貌似xlsx保存之后兼容性不好, 所以选择2003-xls格式
        $excel = request()->file('excel')->getInfo();
        $objPHPExcel = \PHPExcel_IOFactory::load($excel['tmp_name']);//读取上传的文件
        $arrExcel = $objPHPExcel->getSheet(0)->toArray();//获取其中的数据

        if (count($arrExcel) <= 2) {
            return json(array('code'=>401, 'msg'=>'你需要填写至少一行的样机项目'));
        }
        // 先判断fix_no是否有重复
        $isDuplicate = false;
        $duplicateArray = array();
        for ($i = 2; $i < count($arrExcel); $i++) {
            $fixed_no = $arrExcel[$i][0];
            $model_name = $arrExcel[$i][1];
            $res = Db::table('itd.d_main_engine')->where('fixed_no', $fixed_no)->find();

            if (null != $res) {
                $isDuplicate = true;
                array_push($duplicateArray, array('id'=>$fixed_no, 'name'=>$model_name));
            }
        }

        if ($isDuplicate) {
            return json(array('code'=>301, 'msg'=>'重复数据如下, 你需要重新填写', 'detail'=>$duplicateArray));
        }

        // 判断是否有出错的数据
        $hasError = false;  $errorArray = array();
        for ($i = 2; $i < count($arrExcel); $i++) {
            // 定义键名
            $key = array('fixed_no', 'MODEL_NAME', 'SERIAL_NO', 'type', 'department', 'section_manager', 'user_name',
                    'model_status', 'start_date', 'predict_date', 'invoice_no', 'serial_number', 'CPU', 'screen_size', 'MEMORY',
                    'HDD', 'cd_rom', 'location', 'remark');

            $data = array();
            for ($j = 0; $j < count($arrExcel[$i]); $j++) {
                $data[$key[$j]] = $arrExcel[$i][$j];
            }

            // 时间转换
            $data['start_date'] = date('Y-m-d H:i:s', strtotime($data['start_date']));
            $data['predict_date'] = date('Y-m-d H:i:s', strtotime($data['predict_date']));
            // 课转换
            $keyS = array_search($data['section_manager'], $this->sectionArray);
            if ($keyS) {
                $data['section_manager'] = $keyS;
            }
            // 部门转换
            $keyD = array_search($data['department'], $this->departArray);
            if ($keyD) {
                $data['department'] = $keyD;
            }

            // 状态转换
            $keySt = array_search($data['model_status'], $this->statusArray);
            $data['model_status'] = $keySt; // 不是很需要转换, 0为假; 1以后为真
            try{
                Db::table('itd.d_main_engine')->insert($data);
            }catch (Exception $e){
                $msg = $e->getMessage();
                array_push($errorArray, array('data'=>$data, 'msg'=>$msg));
            }
        }

        if (empty($errorArray)) {
            return json(array('code'=>201, 'msg'=>'插入数据成功!', 'detail'=>''));
        } else {
            if (count($errorArray) < (count($arrExcel) - 2)) {
                return json(array('code'=>101, 'msg'=>'部分数据插入失败! 请确认', 'detail'=>$errorArray));
            }
            return json(array('code'=>101, 'msg'=>'全部数据插入失败! 请确认', 'detail'=>$errorArray));
        }
    }

    private function getSearchCondition($formData) {
        $map = array(); // 查询条件

        if ($formData) {
            $formData = json_decode($formData);
            if (!empty($formData->fixed)) {
                $map['fixed_no'] = $formData->fixed;
            }
            if (!empty($formData->names)) {
                $map['MODEL_NAME'] = ['like', '%' . $formData->names . '%'];
            }
            if (!empty($formData->serial)) {
                $map['SERIAL_NO'] = $formData->serial;
            }
            if (!empty($formData->type)) {
                $map['type'] = ['like', '%' . $formData->type . '%'];
            }
            if (!empty($formData->user)) {
                $map['user_name'] = $formData->user;
            }
            if (!empty($formData->location)) {
                $map['location'] = $formData->location;
            }
            if (null != $formData->status) {
                $map['model_status'] = $formData->status;
            }
            if (!empty($formData->depart)) {
                $map['department'] = $formData->depart;
            }
            if (!empty($formData->section)) {
                $map['section_manager'] = $formData->section;
            }
        }

        return $map;
    }

    private function getMailTemplate($user, $subject) {

        return
            '<html charset="utf-8">'.
                '<div class="mail_box">'.
                    '<pre style="font-family: Time New Roman; font-size:15px;">' .
                        '<p></p><p></p>'.
                        '<p>'.$subject.'<p>'.
                        '<p>'. 'From:'. $user . '<p>'.
                         '<p></p><p></p>'.

                        '<p><span>Please check it and judge if you approve or not. Please check in <a href="http://172.30.52.45/itd/">Sample PC Managerment System</a> for details.</span><p>'.

                        '<p>Thanks&BestRegards!</p>'.

                    '</pre>'.
                '</div>'.
            '</html>';
    }
}