<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class S_user extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('saepush_model');
    }

    public function login() {
        if ($this->isLogin()) {
            $this->setCode(1000);
            $this->setResult($this->userinfo);
        } else {
            $pdata = $this->input->post();
            $uid = $this->doLogin($pdata);
            if ($uid > 0) {
                $this->setCode(1000);
                $userinfo = $this->user_model->getUserInfo($uid);
                $this->setResult($userinfo);
            } else {
                $this->setCode(1001);
            }
        }
        $this->reply();
    }

    private function doLogin($data) {
        $callback = null;
        $status = $this->user_model->doLogin($data, $callback);
        if ($status) {
            $this->user_model->setLogin($callback);
            return $callback;
        }
        return -1;
    }

    public function register() {
        $pdata = $this->input->post();
        $this->load->helper('array');
        $data = elements(array('email', 'password', 'nickname'), $pdata, false);
        $data = array_filter($data);
        $callback = null;
        if ($this->user_model->doReg($data, $callback)) {
            $userinfo = $this->user_model->getUserInfo($callback);
            $this->setCode(1000);
            $this->setResult($userinfo);
        } else if ($callback == 'EmailExisted') {
            $this->setCode(1008);
        } else {
            $this->setCode(1005);
        }
        $this->reply();
    }

    public function getUserInfo() {
        if ($this->isLogin()) {
            $this->setCode(1000);
            $this->setResult($this->userinfo);
        } else {
            $this->setCode(1002);
        }
        $this->reply();
    }

    public function logout() {
        $uid = $this->userinfo['uid'];
        $this->user_model->setLogout();
        if (!empty($uid)) {
            $this->saepush_model->updateSAEToken($uid, "");
        }

        $this->setCode(1000);
        $this->reply();
    }

    public function updateSaeToken() {
        $pdata = $this->input->post();
        $uid = $this->doLogin($pdata);

        if ($uid != $pdata['uid']) {
            $this->setCode(1009);
        } else {
            $token = $this->input->post('saetoken');
            $token = trim($token);
            $this->saepush_model->updateSAEToken($uid, $token);
            $this->setCode(1000);
        }
        $this->reply();
    }

    public function getUnreadComment() {
        if (!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $this->load->model('saepush_model');
            $this->saepush_model->pushUnreadComment($this->userinfo['uid']);
            $this->setCode(1000);
        }
        $this->reply();
    }

    public function getCommentList() {
        if (!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $page = $this->input->post('page');
            $comments = $this->comment_model->getCommentByUser($this->userinfo['uid'], $page);
            if (empty($comments)) {
                $this->setCode(1003);
            } else {
                $this->comment_model->setCommentReadByUser($this->userinfo['uid']);
                $this->addResult('commentlist', $comments);
                $this->setCode(1000);
            }
        }
        $this->reply();
    }

    public function getNewComment() {
        if (!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $ltime = $this->input->post('last_time');
            $comments = $this->comment_model->getCommentByUser($this->userinfo['uid'], 0, $ltime);
            if (empty($comments)) {
                $this->setCode(1003);
            } else {
                $this->addResult('commentlist', $comments);
                $this->setCode(1000);
            }
        }
        $this->reply();
    }

}
