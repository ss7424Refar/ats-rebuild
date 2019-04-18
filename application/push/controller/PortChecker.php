<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-11
 * Time: 下午12:34
 */
namespace app\push\controller;

/*
 * PortChecker
 */


class PortChecker
{
    public function getPortInfo(){
        $detectFileName = config('ats_pe_test_pc'). config('ats_test_pc_file'). config('ats_file_suffix');

        $jsonResult = array();

        if (file_exists($detectFileName)) {

            $file = fopen($detectFileName, 'r');

            $line = 0;
            $pushArray=array();
            while ($data = fgetcsv($file)) {
                $line++;
                if ($line >= 2){
                    $tmpArray = array('machine' => $data[1], 'machineId'=> $data[2],
                        'LANIP'=>$data[3], 'ShelfId_SwitchId'=>$data[4]. '_'. $data[5]);
                    array_push($pushArray, $tmpArray);

                }

            }
            fclose($file);
            $jsonResult['result'] = $pushArray;
            $jsonResult['message'] = date("Y-m-d H:i:s", time()) .' Detected';

        } else {
            $jsonResult['message'] = date("Y-m-d H:i:s", time()) .' File Not Exist, disconnect';
            $jsonResult['result'] = null;

        }
        return json_encode($jsonResult) . "\n";
    }

}