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

header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type'); // 这个要设置否则bs-table无法加载

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

        $formData = $this->request->param('formData');

        $map = $this->getSearchCondition($formData);

        $jsonRes = array();

//        $res = Db::query('select fixed_no, MODEL_NAME, model_status, user_name, department, section_manager,
//                                start_date, predict_date from itd.d_main_engine order by instore_date desc limit ?, ?', [$offset, $pageSize]);

        $res = Db::table('itd.d_main_engine')->where($map)->order('instore_date desc')->limit($offset, $pageSize)->select();
        for ($i = 0; $i < count($res); $i++) {
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

        ini_set('memory_limit','200M');
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
                $map['type'] = $formData->type;
            }
            if (!empty($formData->user)) {
                $map['user_name'] = $formData->user;
            }
            if (!empty($formData->location)) {
                $map['location'] = $formData->location;
            }
            if (!empty($formData->status)) {
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
}