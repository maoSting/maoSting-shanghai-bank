<?php

namespace Bank\Actions;

use Bank\Exceptions\InvalidResponseException;
use Bank\Kernel\BasicBank;
use Bank\Tools\Cache;
use Bank\Tools\XML;

class Main extends BasicBank {

    public function setSessionId($session = null) {
        $this->sessionId = $session;
    }

    /**
     * 获取session id
     *
     * @return mixed|null
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \Bank\Exceptions\LocalCacheException
     * @throws \ErrorException
     * Author: DQ
     */
    public function getSessionId() {
        $url = $this->getSessionUrl();

        $serialNo = $this->getSerialNo();
        $reqTime  = $this->getReqTime();
        $userID   = $this->config['userID'];
        $userPWD  = $this->config['userPWD'];

        $xml = sprintf('<?xml version="1.0" encoding="GBK"?><BOSEBankData><opReq><serialNo>%s</serialNo><reqTime>%s</reqTime><ReqParam><userID>%s</userID><userPWD>%s</userPWD></ReqParam></opReq></BOSEBankData>', $serialNo, $reqTime, $userID, $userPWD);

        $sign = $this->getSign($xml);

        $response = $this->httpPostJsonLogin($url, ['reqData' => $sign, 'opName' => 'CebankUserLogon1_1Op']);
        $array    = explode('|', $response);
        if (count($array) != 2 || !isset($array[0]) || !isset($array[1])) {
            throw new InvalidResponseException('get seesion id error');
        }

        $sessionId = $array[0];
        if (empty($sessionId)) {
            throw new InvalidResponseException('get seesion id error');
        }

        Cache::setCache('session', $sessionId, 540);
        $this->setSessionId($sessionId);

        return $sessionId;
    }

    /**
     * 获取 session
     *
     * @return null
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \Bank\Exceptions\LocalCacheException
     * @throws \ErrorException
     * Author: DQ
     */
    public function getSession() {
        $sessionId = $this->sessionId;
        if (empty($sessionId)) {
            $cacheSession = Cache::getCache('session');
            if (empty($cacheSession)) {
                $cacheSession = $this->getSessionId();
                // 有效时间10 min -1 做冗余
                Cache::setCache('session', $cacheSession, 540);
            }
            $this->sessionId = $cacheSession;
        }

        return $this->sessionId;
    }

    /**
     * 智能付款
     *
     * @param        $acno  付款实账号
     * @param        $opac  收款账号
     * @param        $name  收款账号户名
     * @param        $pbno  人行支付系统行号
     * @param int    $tram  金额
     * @param null   $viracno   付款虚账号
     * @param null   $usag  用途  汉字占2个字节，最多支持35个汉字
     * @param null   $remk  备注  汉字占2个字节，最多支持65个汉字
     * @param string $path  汇路编码
     * @param null   $predate   预约转账日期时间
     *
     * @return null
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \Bank\Exceptions\LocalCacheException
     * @throws \ErrorException
     * Author: DQ
     */
    public function transferCrossBank($acno, $opac, $name, $pbno, $tram = 0, $viracno = null, $usag = null, $remk = null, $path = self::PATH_SMART, $predate = null) {
        $url      = $this->getNormalUrl();
        $serialNo = $this->getSerialNo();
        $reqTime  = $this->getReqTime();

        $xml  = '<?xml version="1.0" encoding="GBK"?><BOSEBankData>' . sprintf('<opReq><serialNo>%s</serialNo>', $serialNo) . sprintf('<reqTime>%s</reqTime>', $reqTime) . sprintf('<ReqParam><ACNO>%s</ACNO>', $acno) . sprintf('<VIRACNO>%s</VIRACNO>', $viracno) . sprintf('<OPAC>%s</OPAC>', $opac) . sprintf('<NAME>%s</NAME>', $name) . sprintf('<PBNO>%s</PBNO>', $pbno) . sprintf('<TRAM>%.2f</TRAM>', $tram) . sprintf('<USAG>%s</USAG>', $usag) . sprintf('<REMK>%s</REMK>', $remk) . sprintf('<PATH>%s</PATH>', $path) . sprintf('<PREDATE>%s</PREDATE>', $predate) . '</ReqParam></opReq></BOSEBankData>';
        $sign = $this->getSign($xml);

        $sessionId = $this->getSession();

        $response = $this->httpPostJson($url, [
            'dse_sessionId' => $sessionId,
            'reqData'       => $sign,
            'opName'        => 'transferCrossBank1_1Op'
        ]);

        $this->response = $response;

        $result = isset($response['opRep']['opResult']['T24S']) ? $response['opRep']['opResult']['T24S'] : null;

        return $result;
    }

    /**
     * 查询转账进度
     *
     * @param $osno
     *             注意这是要转账流水号
     *
     * @return null
     * @throws \Bank\Exceptions\InvalidResponseException
     * @throws \Bank\Exceptions\LocalCacheException
     * @throws \ErrorException
     * Author: DQ
     */
    public function queryTransferResult($osno) {
        $url      = $this->getNormalUrl();
        $serialNo = $this->getSerialNo();
        $reqTime  = $this->getReqTime();

        $xml  = '<?xml version="1.0" encoding="GBK"?><BOSEBankData>' . sprintf('<opReq><serialNo>%s</serialNo>', $serialNo) . sprintf('<reqTime>%s</reqTime>', $reqTime) . sprintf('<ReqParam><OSNO>%s</OSNO>', $osno)  . '</ReqParam></opReq></BOSEBankData>';
        $sign = $xml;

        $sessionId = $this->getSession();

        $response = $this->httpPostJson($url, [
            'dse_sessionId' => $sessionId,
            'reqData'       => $sign,
            'opName'        => 'queryTransferResult2_1Op'
        ]);

        $this->response = $response;

        $result = isset($response['opRep']['opResult']) ? $response['opRep']['opResult'] : null;

        return $result;
    }

}