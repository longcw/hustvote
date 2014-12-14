<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $userinfo = array('uid' => null);

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('right_model');
        $uid = $this->session->userdata('uid');

        if (!empty($uid)) {
            $this->userinfo = $this->user_model->getUserInfo($uid);
        }
    }

    public function _getUserIdentification() {
        $identify['uid'] = empty($this->userinfo['uid']) ? null : $this->userinfo['uid'];
        $identify['email'] = empty($this->userinfo['uid']) ? null : $this->userinfo['email'];
        $identify['session_id'] = $this->session->userdata('session_id');
        $identify['ip_address'] = $this->session->userdata('ip_address');
        $identify['is_verified'] = $this->userinfo['is_verified'];
        return $identify;
    }

}
