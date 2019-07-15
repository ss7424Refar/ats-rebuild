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

    protected $infoMsg = array(); // 推送给前台的数组
    protected $ipPool = array(); // 用来存放ip
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
        $ip = $connection->getRemoteIp();
        Log::record('new connection from ip '. $ip);

        if (array_key_exists($ip, $this->ipPool)) {
            $this->ipPool[$ip] = $this->ipPool[$ip] + 1;
        } else {
            $this->ipPool[$ip] = 1;
        }

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        $ip = $connection->getRemoteIp();
        Log::record('disconnect connection from ip '. $ip);

        if (array_key_exists($ip, $this->ipPool)) {
            $res = $this->ipPool[$ip] - 1;
            if (0 == $res) {
                // 移除这个ip
                unset($this->ipPool[$ip]);
            } else {
                $this->ipPool[$ip] = $res;
            }
        }

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

            $infoMsg['clients'] = $clients;
            $infoMsg['ipCounts'] = count($this->ipPool);

            // 遍历当前进程所有的客户端连接
            foreach($worker->connections as $connection) {
                $connection->send(json_encode($infoMsg));
            }
        });
    }
}