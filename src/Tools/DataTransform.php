<?php

namespace Bank\Tools;

use Bank\Exceptions\InvalidResponseException;

class DataTransform {

    /**
     *
     * @param $xml
     *
     * @return mixed
     * @throws \Bank\Exceptions\InvalidResponseException
     *                                                  注意编码
     * Author: DQ
     */
    public static function xml2arr($xml) {
        $encoding = mb_convert_encoding($xml, 'GBK', 'UTF-8');
        $encoding = str_replace('GBK', 'UTF-8', $encoding);
        $rs       = XML::parse($encoding);
        if (empty($rs)) {
            throw new InvalidResponseException('invalid response.', 0);
        }
        if (isset($rs['opRep']['retCode']) && $rs['opRep']['retCode'] != 0) {
            throw new InvalidResponseException($rs['opRep']['errMsg'], $rs['opRep']['retCode']);
        }

        return $rs;
    }
}