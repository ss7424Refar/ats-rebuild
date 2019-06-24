<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-11
 * Time: 下午4:18
 */

namespace app\services\controller;

use think\Controller;
use think\Db;
use PHPExcel; // 导出
use PHPExcel_Style_Fill;

/*
 * interface for ats
 */
class MachineDetail extends Controller {
    /**
     * http://localhost/ats/services/MachineDetail/getMachineInfo?fix_no=0512013
     *
     * @return false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMachineInfo(){
        $no = $this->request->param('fix_no');

//        Db::connect('db_config2');
//        $res = Db::table('d_main_engine')
//            ->where('fixed_no','=', $no)
//            ->select();

        $res = Db::query('SELECT * FROM itd.d_main_engine where fixed_no = ?;', [$no]);
        // 没数据返回'[]'
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * http://localhost/ats/services/MachineDetail/export?user=朱林,徐万亮
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function export() {

        $user = $this->request->param('user');
        $user = explode(',', $user);

        $list = Db::table('itd.d_main_engine')->where('user_name','in',$user)->order('MODEL_NAME')->select();

        dump($list);
        //3.实例化PHPExcel类
        $objPHPExcel = new PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);
        //5.设置表格头（即excel表格的第一行）
        $objPHPExcel->setActiveSheetIndex(0);

        //设置A列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格宽度
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(7);

        $letter = ['A', 'B', 'C', 'D'];//列坐标
        $header= ['No', 'Machine Id', 'Machine Name', 'User']; //表头,名称可自定义
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
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), $i+1);//No
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2), $list[$i]['fixed_no']);//Machine Id
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+2), $list[$i]['MODEL_NAME']);//Machine Name
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+2), $list[$i]['user_name']);//user_name
        }
        //7.设置保存的Excel表格名称
        $filename = 'machine_user_'.time().'.xls';
        //8.设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle('used');

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
}