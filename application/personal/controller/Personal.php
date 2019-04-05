<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-4-3
 * Time: 下午9:52
 */

namespace app\personal\controller;

use think\Controller;
use think\Db;
use PHPExcel; // 导出
use PHPExcel_Style_Fill;

/*
 * interface for personal
 */
class Personal extends Controller {
    /**
     * 导入excel
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function inputExcel(){
        $users = $this->request->param('users');
        $excel = request()->file('excel')->getInfo();
        $objPHPExcel = \PHPExcel_IOFactory::load($excel['tmp_name']);//读取上传的文件
        $arrExcel = $objPHPExcel->getSheet(0)->toArray();//获取其中的数据

        //转换为一维数组
        $items = Array();
        for($i = 0; $i < count($arrExcel); $i++) {
            if (null != $arrExcel[$i][0]) {
                $items[$i] = $arrExcel[$i][0];
            }
        }

        // 根据users查询所借的机子
        $list = $this->getUsersMachineList($users);

        // 比对
        $this->createToExcel($list, $items);
    }

    public function outputExcel(){
        $items = $this->request->param('items');
        $items = json_decode($items);

        // 生成excel
        //3.实例化PHPExcel类
        $objPHPExcel = new PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);

        //设置A列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格宽度
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);


        //6.循环刚取出来的数组，将数据逐一添加到excel表格。
        for($i=0; $i<count($items); $i++){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+1), $items[$i]);//id
        }
        //7.设置保存的Excel表格名称
        $filename = 'machine_saveId_'.time().'.xls';
        //8.设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle('machineId');

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


    /**
     * 比较导出成excel
     */
    public function compareToExcel(){
        $users = $this->request->param('users');
        $items = $this->request->param('items');
        $items = json_decode($items);

        // 根据users查询所借的机子
        $list = $this->getUsersMachineList($users);

        // 比对
        $this->createToExcel($list, $items);
    }

    private function getUsersMachineList($users){
        $user = explode(',', $users);

        // 根据users查询所借的机子
        $list = Db::table('itd.d_main_engine')->where('user_name','in', $user)->order('MODEL_NAME')->select();

        return $list;
    }

    private function createToExcel($list, $items){
        for ($i = 0; $i < count($list); $i++) {
            if (in_array($list[$i]['fixed_no'], $items)) {
                $list[$i]['flag'] = 'found in scan';
            } else {
                $list[$i]['flag'] = 'not found in scan';
            }
        }

        dump($list);
        // 生成excel
        //3.实例化PHPExcel类
        $objPHPExcel = new PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);

        //设置A列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格宽度
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(7);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);

        $letter = ['A', 'B', 'C', 'D', 'E'];//列坐标
        $header= ['No', 'Machine Id', 'Machine Name', 'User', 'Desc']; //表头,名称可自定义
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
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($i+2), $list[$i]['flag']);//flag

            // 设置行的颜色
            if ('found in scan' == $list[$i]['flag']) {
                // 设置填充颜色(灰色)
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+2).':E'.($i+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+2).':E'.($i+2))->getFill()->getStartColor()->setARGB('FF808080');
            }
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