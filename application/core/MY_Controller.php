<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $userinfo = array('uid' => null);
    private $response = array(
        'sid' => '',
        'code' => 4000,
        'message' => '',
        'result' => array("")
    );

    function __construct() {
        parent::__construct();
        //用于客户端传递sid
//        $sid = $this->input->get('sid');
//        if (!empty($sid)) {
//            session_id($sid);
//        }
        $this->load->library('session');
        $this->load->model('comment_model');
        $this->load->model('user_model');
        $this->load->model('right_model');
        $uid = $this->session->userdata('uid');

        if (!empty($uid)) {
            $this->userinfo = $this->user_model->getUserInfo($uid);
        }
    }

    public function getUserIdentification() {
        $identify['uid'] = empty($this->userinfo['uid']) ? null : $this->userinfo['uid'];
        $identify['email'] = empty($this->userinfo['uid']) ? null : $this->userinfo['email'];
        $identify['session_id'] = $this->session->userdata('session_id');
        $identify['ip_address'] = $this->session->userdata('ip_address');
        $identify['is_verified'] = empty($this->userinfo['uid']) ? 0 : $this->userinfo['is_verified'];
        return $identify;
    }

    public function setCode($code) {
        $this->response['code'] = $code;
    }

    public function setMessage($msg) {
        $this->response['message'] = $msg;
    }

    public function setResult($result) {
        $this->response['result'] = $result;
    }

    public function addResult($key, $value) {
        $this->response['result'][$key] = $value;
    }

    public function reply() {
        $this->response['sid'] = $this->session->userdata('session_id');
        if (empty($this->response['message'])) {
            $code = $this->response['code'];
            $this->response['message'] = $this->errorhandler->getCodeMsg($code);
        }
        
        unset($this->response['result'][0]);
        if (empty($this->response['result'])) {
            $this->addResult("empty", true);
        }
        $this->output->set_content_type('application/json')
                ->set_output(json_encode($this->response));
    }

    public function isLogin() {
        return !empty($this->userinfo['uid']);
    }

}
