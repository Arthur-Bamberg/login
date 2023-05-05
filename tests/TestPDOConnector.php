<?php
require_once 'BaseTest.php';
require_once '../utils/PDOConnector.php';

class TestPDOConnector extends BaseTest {
    public function __construct() {
        parent::__construct('PDOConnector');
    }
}