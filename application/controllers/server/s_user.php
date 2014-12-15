<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class S_user extends MY_Controller {

    private $response = array(
        'code' => 1000,
        'message' => 'ok',
        'result' => ''
    );

    public function __construct() {
        parent::__construct();
    }

    public function login() {
        $this->setCode(1000);
        $result = $this->userinfo;
        $this->setResult($result);
        $this->reply();
    }
    
    public function __destruct() {
        
    }

}
