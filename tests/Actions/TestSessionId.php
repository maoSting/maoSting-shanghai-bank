<?php

namespace Bank\tests;

use Bank\Actions\Main;

class TestSessionId extends BasicTest {

    public function testGetSessionId() {
        $lib = new Main($this->_config);

        $sessionId = $lib->getSessionId();

        $this->assertEquals(strlen($sessionId), 40, 'Actions/Main getSessionId error');
    }

}