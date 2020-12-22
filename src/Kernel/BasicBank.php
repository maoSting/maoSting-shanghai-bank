<?php

namespace Bank\Kernel;

use Bank\Exceptions\InvalidArgumentException;
use Bank\Exceptions\InvalidResponseException;
use Bank\Tools\DataArray;
use Bank\Tools\DataTransform;
use Bank\Tools\RequestTool;
use Bank\Tools\SocketClient;
use Bank\Tools\XML;

class BasicBank {

    const VERSION = '0.0.1';

    // 汇路编码
    const PATH_SMART = '01';// 智能
    const PATH_SUPER = '02';// 超网

    public $config;

    public $sessionId = null;

    // access token
    public $sign = '';

    private $serialNo = null;

    private $reqTime = null;

    // 当前请求方法
    private $_currentMethod = [];

    // 是否是重试
    private $_isTry = false;

    // 响应内容
    public $response = null;

    public function __construct(array $options) {
        date_default_timezone_set("PRC");

        if (empty($options['userID'])) {
            throw new InvalidArgumentException('miss config [userID]');
        }
        if (empty($options['userPWD'])) {
            throw new InvalidArgumentException('miss config [userPWD]');
        }
        // 付款账户
        if (empty($options['accountNo'])) {
            throw new InvalidArgumentException('miss config [accountNo]');
        }
        // 签名 ip:port
        if (empty($options['sign_host'])) {
            throw new InvalidArgumentException('miss config [sign_host]');
        }
        // ssl client host
        if (empty($options['ssl_host'])) {
            throw new InvalidArgumentException('miss config [ssl_host]');
        }

        $this->config = new DataArray($options);

    }

    /**
     *
     * @return string
     * Author: DQ
     */
    public function getVersion() {
        return self::VERSION;
    }

    /**
     * 遇到错误是否再次尝试
     *
     * @param bool $bool
     *                  true 再次尝试
     *                  false 不会再次尝试
     *                  Author: DQ
     */
    public function tryAgain($bool = false) {
        $this->_isTry = $bool == false;
    }

    /**
     * 登陆交易
     *
     * @return string
     * Author: DQ
     */
    public function getSessionUrl() {
        $url = sprintf('http://%s/CM/APISessionReqServlet', $this->config['ssl_host']);

        return $url;
    }

    /**
     * 一般交易
     *
     * @return string
     * Author: DQ
     */
    public function getNormalUrl() {
        $url = sprintf('http://%s/CM/APIReqServlet', $this->config['ssl_host']);

        return $url;
    }

    public function setSerialNo($serialNo = null) {
        $this->serialNo = $serialNo;
    }

    /**
     * 请求序列号
     *
     * @return null|string
     * Author: DQ
     */
    public function getSerialNo() {
        if (empty($this->serialNo)) {
            $this->serialNo = date('YmdHis') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        return $this->serialNo;
    }

    public function setReqTime($time = null) {
        $this->reqTime = $time;
    }

    /**
     * 请求日期
     *
     * @param bool $new
     *
     * @return false|null|string
     * Author: DQ
     */
    public function getReqTime($new = true) {
        if ($new) {
            $this->reqTime = date('YmdHis');
        }

        return $this->reqTime;
    }

    /**
     * 获取签名数据
     *
     * @param string $xml
     *
     * @return null
     * @throws \Bank\Exceptions\SocketException
     * Author: DQ
     */
    public function getSign($xml = '') {
        $data         = sprintf('<?xml version="1.0" encoding="GBK"?><msg><msg_head><msg_type>0</msg_type><msg_id>1005</msg_id><msg_sn>0</msg_sn><version>1</version></msg_head><msg_body><origin_data_len>%d</origin_data_len><origin_data>%s</origin_data></msg_body></msg>', strlen($xml) * 8, $xml);
        $socketClient = new SocketClient($this->config['sign_host']);
        $response     = XML::parse($socketClient->send($data));
        $socketClient->close();

        $sign = isset($response['msg_body']['signed_data']) ? $response['msg_body']['signed_data'] : null;

        return $sign;
    }

    /**
     * 注册请求
     *
     * @param       $method
     *                        方法
     * @param array $arguments
     *                        参数
     *
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * @throws \ListenRobot\Exceptions\LocalCacheException
     * Author: DQ
     */
    protected function registerApi($method, $arguments = []) {
        $this->_currentMethod = ['method' => $method, 'arguments' => $arguments];
    }

    /**
     * 登录单单独请求，因为返回不是一个标准的xml
     *
     * @param       $url
     * @param       $data
     * @param array $headers
     *
     * @return mixed|null
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \ErrorException
     * Author: DQ
     */
    public function httpPostJsonLogin($url, $data, $headers = []) {
        try {
            $headers['user-agent']   = 'MSIE';
            $headers['content-type'] = 'application/x-www-form-urlencoded';

            $this->registerApi(__FUNCTION__, func_get_args());
            $response = RequestTool::post($url, $data, $headers);

            $response = mb_convert_encoding($response, 'UTF-8', 'GBK');
            $response = str_replace('GBK', 'UTF-8', $response);

            return $response;
        } catch (InvalidResponseException $e) {
            if (!$this->_isTry) {
                $this->_isTry = true;

                return call_user_func_array([
                    $this,
                    $this->_currentMethod['method']
                ], $this->_currentMethod['arguments']);
            }
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * post 请求返回
     *
     * @param       $url
     * @param       $data
     * @param array $headers
     *
     * @return mixed
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \ErrorException
     * Author: DQ
     */
    public function httpPostJson($url, $data, $headers = []) {
        try {
            $headers['user-agent']   = 'MSIE';
            $headers['content-type'] = 'application/x-www-form-urlencoded';

            $this->registerApi(__FUNCTION__, func_get_args());
            $response = RequestTool::post($url, $data, $headers);

            return DataTransform::xml2arr($response);
        } catch (InvalidResponseException $e) {
            if (!$this->_isTry) {
                $this->_isTry = true;

                return call_user_func_array([
                    $this,
                    $this->_currentMethod['method']
                ], $this->_currentMethod['arguments']);
            }
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

}