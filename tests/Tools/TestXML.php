<?php

namespace Bank\tests;

use Bank\Tools\XML;
use PHPUnit\Framework\TestCase;

class TestXML extends TestCase {

    public function testBuild() {
        $sn   = date('YmdHis') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $time = time();

        $template = sprintf('<xml version="1.0" encoding="GBK"><BOSEBankData><opReq><serialNo>%s</serialNo><reqTime>%s</reqTime><ReqParam><userID>123456</userID><userPWD>654321</userPWD></ReqParam></opReq></BOSEBankData></xml>', $sn, $time);

        $data['BOSEBankData']['opReq']['serialNo']            = $sn;
        $data['BOSEBankData']['opReq']['reqTime']             = $time;
        $data['BOSEBankData']['opReq']['ReqParam']['userID']  = '123456';
        $data['BOSEBankData']['opReq']['ReqParam']['userPWD'] = '654321';

        $xml = XML::build($data, 'xml', null, ' version="1.0" encoding="GBK"');
        $this->assertEquals($template, $xml, 'Bank/Tools/XML build error');
    }

    public function testParse() {
        $xml    = '<?xml version="1.0" encoding="UTF-8"?><msg><msg_head><msg_type>1</msg_type><msg_id>1005</msg_id><msg_sn>0</msg_sn><version>1</version></msg_head><msg_body><signed_data>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</signed_data></msg_body></msg>';
        $result = XML::parse($xml);
        $count = 0;
        isset($result['msg_head']) && $count++;
        isset($result['msg_head']['msg_type']) && $count++;
        isset($result['msg_head']['msg_id']) && $count++;
        isset($result['msg_head']['msg_sn']) && $count++;
        isset($result['msg_head']['version']) && $count++;

        isset($result['msg_body']) && $count++;
        isset($result['msg_body']['signed_data']) && $count++;

        $this->assertEquals($count, 7, 'Bank/Tools/XML parse error');
    }
}