<?php

namespace Bank\tests;

use Bank\Tools\XML;
use PHPUnit\Framework\TestCase;

class TestXMLError extends TestCase {

    public function testParse() {
        $xml    = '<?xml version="1.0" encoding="GBK" ?><BOSEBankData><opRep><serialNo>20201217145526281</serialNo><retCode>0</retCode><errMsg>test</errMsg><opResult><userName>test</userName><corpName>174704</corpName></opResult></opRep></BOSEBankData>';
        $result = XML::parse($xml);
        $count  = 0;
        isset($result['opRep']) && $count++;
        isset($result['opRep']['serialNo']) && $count++;
        isset($result['opRep']['retCode']) && $count++;
        isset($result['opRep']['errMsg']) && $count++;
        isset($result['opRep']['opResult']) && $count++;

        isset($result['opRep']['opResult']['userName']) && $count++;
        isset($result['opRep']['opResult']['corpName']) && $count++;

        $this->assertEquals($count, 7, 'Bank/Tools/XML parse error');
    }

    public function testParseTransfer() {
        $xml = '<?xml version="1.0" encoding="GBK" ?><BOSEBankData><opRep><serialNo>20201217172512493</serialNo><retCode>0</retCode><errMsg>业务已受理</errMsg><opResult><T24S>FT20352270139203</T24S><T24D>20201217</T24D></opResult></opRep></BOSEBankData>';

        $result = XML::parse($xml);
        $count  = 0;
        isset($result['opRep']) && $count++;
        isset($result['opRep']['serialNo']) && $count++;
        isset($result['opRep']['retCode']) && $count++;
        isset($result['opRep']['errMsg']) && $count++;
        isset($result['opRep']['opResult']) && $count++;

        isset($result['opRep']['opResult']['userName']) && $count++;
        isset($result['opRep']['opResult']['corpName']) && $count++;

        $this->assertEquals($count, 7, 'Bank/Tools/XML parse error');
    }

}