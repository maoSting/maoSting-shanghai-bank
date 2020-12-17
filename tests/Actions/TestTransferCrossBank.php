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
class TestTransferCrossBank extends BasicTest {

    public function testGetSessionId() {
        $lib = new Main($this->_config);

        $serial = $lib->transferCrossBank($this->_data['ACNO'], $this->_data['OPAC'], $this->_data['NAME'], $this->_data['PBNO'], $this->_data['TRAM'], $this->_data['VIRACNO']);

        $this->assertEquals(strlen($serial), 16, 'Actions/Main transferCrossBank error');
    }

}