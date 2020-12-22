<?php

namespace Bank\Actions;

use Bank\Exceptions\InvalidResponseException;
use Bank\Kernel\BasicBank;
use Bank\Tools\Cache;
use Bank\Tools\XML;

class Main extends BasicBank {

    /**
     * 获取银行列表
     *
     * @return array
     * Author: DQ
     */
    public function getBanks() {
        return [
            '102100099996' => '中国工商银行',
            '103100000026' => '中国农业银行股份有限公司',
            '104100000004' => '中国银行总行',
            '104290003033' => '中国银行上海市分行营业部',
            '105100000017' => '中国建设银行股份有限公司总行',
            '301290000007' => '交通银行',
            '302100011000' => '中信银行股份有限公司',
            '303100000006' => '中国光大银行',
            '304100040000' => '华夏银行股份有限公司总行',
            '305100000013' => '中国民生银行',
            '306581000003' => '广发银行股份有限公司',
            '307584007998' => '平安银行（原深圳发展银行）',
            '308584000013' => '招商银行股份有限公司',
            '309391000011' => '兴业银行总行',
            '310290000013' => '上海浦东发展银行',
            '315456000105' => '恒丰银行',
            '316331000018' => '浙商银行',
            '318110000014' => '渤海银行股份有限公司',
            '325290000012' => '上海银行',
            '403100000004' => '中国邮政储蓄银行有限责任公司',
            '313100000013' => '北京银行',
            '313110000017' => '天津银行股份有限公司',
            '313121006888' => '河北银行股份有限公司',
            '313127000013' => '邯郸市商业银行股份有限公司',
            '313131000016' => '邢台银行股份有限公司',
            '313138000019' => '张家口市商业银行股份有限公司',
            '313141052422' => '承德银行股份有限公司',
            '313143005157' => '沧州银行',
            '313146000019' => '廊坊银行',
            '313161000017' => '晋商银行股份有限公司',
            '313168000003' => '晋城银行',
            '313191000011' => '内蒙古银行',
            '313192000013' => '包商银行股份有限公司',
            '313205057830' => '鄂尔多斯银行股份有限公司',
            '313222080002' => '大连银行',
            '313223007007' => '鞍山市商业银行',
            '313227000012' => '锦州银行',
            '313227600018' => '葫芦岛银行股份有限公司',
            '313228000276' => '营口银行股份有限公司资金清算中心',
            '313229000008' => '阜新银行结算中心',
            '313241066661' => '吉林银行',
            '313261000018' => '哈尔滨银行结算中心',
            '313261099913' => '龙江银行股份有限公司',
            '313301008887' => '南京银行股份有限公司',
            '313301099999' => '江苏银行股份有限公司',
            '313305066661' => '苏州银行股份有限公司',
            '313312300018' => '江苏长江商业银行',
            '313331000014' => '杭州银行股份有限公司',
            '313332082914' => '宁波银行股份有限公司',
            '313333007331' => '温州银行股份有限公司',
            '313335081005' => '嘉兴银行股份有限公司清算中心(不对外办理业务）',
            '313336071575' => '湖州银行股份有限公司',
            '313337009004' => '绍兴银行股份有限公司营业部',
            '313338707013' => '浙江稠州商业银行',
            '313345001665' => '台州银行股份有限公司',
            '313345010019' => '浙江泰隆商业银行',
            '313345400010' => '浙江民泰商业银行',
            '313391080007' => '福建海峡银行股份有限公司',
            '313393080005' => '厦门银行股份有限公司',
            '313397075189' => '泉州银行股份有限公司(不对外营业)',
            '313421087506' => '南昌银行',
            '313424076706' => '九江银行股份有限公司',
            '313428076517' => '赣州银行股份有限公司',
            '313433076801' => '上饶银行',
            '313451000019' => '齐鲁银行',
            '313452060150' => '青岛银行',
            '313453001017' => '齐商银行',
            '313454000016' => '枣庄银行股份有限公司',
            '313455000018' => '东营市商业银行',
            '313456000108' => '烟台银行股份有限公司',
            '313458000013' => '潍坊银行',
            '313461000012' => '济宁银行股份有限公司',
            '313463000993' => '泰安市商业银行',
            '313463400019' => '莱商银行',
            '313465000010' => '威海市商业银行',
            '313468000015' => '德州银行股份有限公司',
            '313473070018' => '临商银行股份有限公司',
            '313473200011' => '日照银行股份有限公司',
            '313491000232' => '郑州银行',
            '313491099996' => '中原银行股份有限公司',
            '313492070005' => '中原银行股份有限公司开封分行',
            '313493080539' => '洛阳银行',
            '313504000010' => '中原银行股份有限公司漯河分行',
            '313506082510' => '中原银行股份有限公司商丘分行',
            '313513080408' => '中原银行股份有限公司南阳分行',
            '313521000011' => '汉口银行资金清算中心',
            '313521006000' => '湖北银行股份有限公司',
            '313551088886' => '长沙银行股份有限公司',
            '313581003284' => '广州银行',
            '313585000990' => '珠海华润银行股份有限公司清算中心',
            '313586000006' => '广东华兴银行股份有限公司',
            '313591001001' => '广东南粤银行股份有限公司',
            '313602088017' => '东莞银行股份有限公司',
            '313611001018' => '广西北部湾银行',
            '313614000012' => '柳州银行股份有限公司清算中心',
            '313617000018' => '桂林银行股份有限公司',
            '313651099999' => '成都银行',
            '313653000013' => '重庆银行',
            '313655091983' => '自贡市商业银行清算中心',
            '313656000019' => '攀枝花市商业银行',
            '313658000014' => '德阳银行股份有限公司',
            '313659000016' => '绵阳市商业银行',
            '313673093259' => '南充市商业银行股份有限公司',
            '313701098010' => '贵阳市商业银行',
            '313731010015' => '富滇银行股份有限公司运营管理部',
            '313791000015' => '西安银行股份有限公司',
            '313791030003' => '长安银行股份有限公司',
            '313821001016' => '兰州银行股份有限公司',
            '313851000018' => '青海银行股份有限公司营业部',
            '313871000007' => '宁夏银行总行清算中心',
            '313881000002' => '乌鲁木齐市商业银行清算中心',
            '313882000012' => '昆仑银行股份有限公司',
            '314302200018' => '江苏江阴农村商业银行股份有限公司',
            '314305106644' => '太仓农村商业银行',
            '314305206650' => '昆山农村商业银行',
            '314305400015' => '吴江农村商业银行清算中心',
            '314305506621' => '江苏常熟农村商业银行股份有限公司清算中心',
            '314305670002' => '张家港农村商业银行',
            '314581000011' => '广州农村商业银行股份有限公司',
            '314588000016' => '佛山顺德农村商业银行股份有限公司',
            '314641000014' => '海口联合农村商业银行股份有限公司',
            '314653000011' => '重庆农村商业银行股份有限公司',
            '317110010019' => '天津农村合作银行',
            '319361000013' => '徽商银行股份有限公司',
            '322290000011' => '上海农村商业银行',
            '402100000018' => '北京农村商业银行股份有限公司',
            '402241000015' => '吉林省农村信用社联合社（不办理转汇业务）',
            '402301099998' => '江苏省农村信用社联合社(不对外)',
            '402331000007' => '浙江省农村信用社联合社',
            '402332010004' => '宁波鄞州农村合作银行',
            '402361018886' => '安徽省农村信用联社资金清算中心',
            '402391000068' => '福建省农村信用社联合社',
            '402451000010' => '山东省农村信用社联合社(不对外办理业务)',
            '402521000032' => '湖北省农村信用社联合社结算中心',
            '402584009991' => '深圳农村商业银行股份有限公司',
            '402602000018' => '东莞农村商业银行股份有限公司',
            '402611099974' => '广西壮族自治区农村信用社联合社',
            '402641000014' => '海南省农村信用社联合社资金清算中心',
            '402651020006' => '四川省农村信用社联合社',
            '402731057238' => '云南省农村信用社联合社',
            '402871099996' => '宁夏黄河农村商业银行股份有限公司',
            '502290000006' => '东亚银行（中国）有限公司',
            '593100000020' => '友利银行(中国)有限公司',
            '595100000007' => '新韩银行（中国）有限公司',
            '596110000013' => '企业银行（中国）有限公司',
            '597100000014' => '韩亚银行（中国）有限公司',
        ];
    }

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

        $serialNo = date('YmdHis') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
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
     * @param        $acno    付款实账号
     * @param        $opac    收款账号
     * @param        $name    收款账号户名
     * @param        $pbno    人行支付系统行号
     * @param int    $tram    金额
     * @param null   $viracno 付款虚账号
     * @param null   $usag    用途  汉字占2个字节，最多支持35个汉字
     * @param null   $remk    备注  汉字占2个字节，最多支持65个汉字
     * @param string $path    汇路编码
     * @param null   $predate 预约转账日期时间
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

        $xml  = '<?xml version="1.0" encoding="GBK"?><BOSEBankData>' . sprintf('<opReq><serialNo>%s</serialNo>', $serialNo) . sprintf('<reqTime>%s</reqTime>', $reqTime) . sprintf('<ReqParam><OSNO>%s</OSNO>', $osno) . '</ReqParam></opReq></BOSEBankData>';
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