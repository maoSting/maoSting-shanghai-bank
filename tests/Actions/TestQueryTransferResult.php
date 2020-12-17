<?php

namespace Bank\tests;

use Bank\Actions\Main;

/**
 * 智能付款
 *
 *
 * Class TestTransferCrossBank
 * Author: DQ
 * @package Bank\tests
 */
class TestQueryTransferResult extends BasicTest {

    public function testGetSessionId() {
        $lib = new Main($this->_config);

        $query = $lib->queryTransferResult('20201217174708175');
        
        $this->assertEquals(is_array($query), true, 'Actions/Main queryTransferResult error');

        $this->assertEquals(count($query), 4, 'Actions/Main queryTransferResult error');
    }

}