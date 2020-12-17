<?php

namespace Bank\Tools;

use Bank\Exceptions\SocketException;

class SocketClient {

    private $resource = null;

    private $response = null;

    /**
     * SocketClient constructor.
     *
     * @param $host
     * @param $port
     *
     * @throws \Bank\Exceptions\SocketException
     */
    public function __construct($host) {
        $this->resource = stream_socket_client(sprintf("tcp://%s", $host), $errorCode, $errorMsg, 10);
        if (!$this->resource) {
            throw new SocketException(sprintf("socket connect error:%s", $errorMsg), $errorCode);
        }
    }

    /**
     * 发送数据
     *
     * @param string $data
     *
     * @return null
     * @throws \Bank\Exceptions\SocketException
     * Author: DQ
     */
    public function send($data = '') {
        $this->response = null;
        fwrite($this->resource, $data);
        while (!feof($this->resource)) {
            $this->response .= fgets($this->resource, 1024);
        }

        return $this->response;
    }

    /**
     * 关闭socket 连接
     *
     * Author: DQ
     */
    public function close() {
        fclose($this->resource);
    }

}