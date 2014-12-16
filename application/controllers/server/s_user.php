<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class S_user extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function login() {
        $callback = null;
        $pdata = $this->input->post();
        $status = $this->user_model->doLogin($pdata, $callback);
        if($status) {
            $this->user_model->setLogin($callback);
            $userinfo = $this->user_model->getUserInfo($callback);
            $this->setCode(1000);
            $this->setResult($userinfo);
        } else {
            $this->setCode(1001);
        }
        $this->reply();
    }
    
    public function getUserInfo() {
        if($this->isLogin()) {
            $this->setCode(1000);
            $this->setResult($this->userinfo);
        } else {
            $this->setCode(1002);
        }
        $this->reply();
    }

    public function logout() {
        $this->user_model->setLogout();
        $this->setCode(1000);
        $this->reply();
    }

}
