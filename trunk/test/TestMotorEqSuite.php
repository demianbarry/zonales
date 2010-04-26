<?php
require_once('../simpletest/autorun.php');
require_once('bootstrap.php');
require_once('com_eqzonales/TestEq.php');
require_once('com_eqzonales/TestBand.php');

class AllTests extends TestSuite {

    function AllTests() {
        $this->TestSuite('All tests for com_eqzonales');
        $this->addTestCase(new TestEq());
        $this->addTestCase(new TestBand());
    }

}