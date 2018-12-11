<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-12-10
 * Time: 下午2:00
 */
namespace app\push\controller;

use think\worker\Server;

use Workerman\Lib\Timer;

class Worker extends Server
{
    protected $socket = 'websocket://0.0.0.0:2346';

    private $clientMsg = array('port_check');

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {

        if ($this->clientMsg[0] == $data) {
            $port = controller('push/PortChecker');
            $connection->port_timer_id = Timer::add(5, function()use($connection, $data, &$port_timer_id, $port)
            {
                $connection->send($port->getPortInfo());
            });

        }

    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        // 删除定时器
        Timer::del($connection->port_timer_id);
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

//        // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
//        if($worker->id === 0) {
//            Timer::add(5, function()use($worker){
//
//                $port = controller('push/PortChecker');
//
//                foreach($worker->connections as $connection) {
//                    $connection->send($port->getPortInfo());
//                }
//
//
//            });
//        } else if($worker->id === 1) {
//            Timer::add(4, function()use($worker){
//                echo date("Y-m-d H:i:s", time()). '\n' ;
//            });
//        }

    }
}