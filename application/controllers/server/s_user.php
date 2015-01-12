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
            $callback = null;
            $pdata = $this->input->post();
            $status = $this->user_model->doLogin($pdata, $callback);
            if ($status) {
                $this->user_model->setLogin($callback);
                $userinfo = $this->user_model->getUserInfo($callback);
                $this->load->model('saepush_model');
                $this->saepush_model->pushUnreadComment($userinfo['uid']);
                $this->setCode(1000);
                $this->setResult($userinfo);
            } else {
                $this->setCode(1001);
            }
        }
        $this->reply();
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
        if(!empty($uid)) {
            $this->saepush_model->updateSAEToken($uid, "");
        }
        
        $this->setCode(1000);
        $this->reply();
    }

    public function updateSaeToken() {
        if (!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $uid = $this->input->post('uid');
            $token = $this->input->post('saetoken');
            if ($uid != $this->userinfo['uid']) {
                $this->setCode(1006);
            } else {
                $this->saepush_model->updateSAEToken($uid, $token);
                //推送消息
                $this->saepush_model->pushUnreadComment($uid);
                $this->setCode(1000);
            }
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
        if(!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $page = $this->input->post('page');
            $comments = $this->comment_model->getCommentByUser($this->userinfo['uid'], $page);
            if(empty($comments)) {
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
        if(!$this->isLogin()) {
            $this->setCode(1002);
        } else {
            $ltime = $this->input->post('last_time');
            $comments = $this->comment_model->getCommentByUser($this->userinfo['uid'], 0, $ltime);
            if(empty($comments)) {
                $this->setCode(1003);
            } else {
                $this->addResult('commentlist', $comments);
                $this->setCode(1000);
            }
        }
        $this->reply();
    }

}
