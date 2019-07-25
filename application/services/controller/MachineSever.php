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

}