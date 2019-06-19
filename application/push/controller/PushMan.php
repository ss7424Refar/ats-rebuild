<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 19-6-19
 * Time: 下午9:17
 */
namespace app\push\controller;

use think\worker\Server;

use Workerman\Lib\Timer;
use think\Log;

class PushMan extends Server
{
    protected $socket = 'websocket://0.0.0.0:2345';
    protected $processes = 1;
    private $infoMsg = array();

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {

    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {
        Log::record('connection id =====> '. $connection->id);
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        Log::record('connection close id =====> '. $connection->id);
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
        Timer::add(1, function()use($worker)
        {
            Log::record('sending clients counter');
            $clients = count($worker->connections);
            // 遍历当前进程所有的客户端连接
            foreach($worker->connections as $connection) {
                $connection->send($clients);
            }
        });
    }
}