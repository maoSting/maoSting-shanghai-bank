<?php

namespace Bank\Exceptions;

/**
 * 接口参数异常类
 * Class InvalidArgumentException
 * Author: DQ
 * @package Bank\Exceptions
 */
class InvalidArgumentException extends \InvalidArgumentException {
    public $raw = [];

    public function __construct($message = "", $code = 0, $raw = []) {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}