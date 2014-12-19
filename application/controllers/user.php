<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->setFormMessage();
    }

    private function setFormMessage() {
        $this->form_validation->set_message('required', '请填写 %s');
        $this->form_validation->set_message('valid_email', '请填写正确的 %s');
        $this->form_validation->set_message('max_length', '%s 不能超过%s位');
        $this->form_validation->set_message('min_length', '%s 不能少于%s位');
        $this->form_validation->set_message('matches', '两次输入的 %s 不同');
        $this->form_validation->set_message('email_check', '%s已经被注册');
    }

    public function login() {
        if (!empty($this->userinfo['uid'])) {
            //var_dump($this->userinfo);
            redirect();
            return;
        }
        $this->form_validation->set_rules('email', '邮箱', 'trim|required');
        $this->form_validation->set_rules('password', '密码', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->_showLogin(validation_errors());
        } else {
            $data = $this->input->post();
            $data = array_map('trim', $data);
            $callback = null;
            if ($this->user_model->doLogin($data, $callback)) {
                $this->user_model->setLogin($callback);
                redirect();
            } else {
                $this->_showLogin($this->errorhandler->getErrorDes($callback));
            }
        }
    }

    private function _showLogin($error) {
        $header['userinfo'] = $this->userinfo;
        $header['title'] = '登录 HustVote 在线投票';
        $data['error'] = $error;
        $this->load->view('header', $header);
        $this->load->view('login', $data);
        $this->load->view('footer');
    }

    private function _showRegister($error) {
        $header['userinfo'] = $this->userinfo;
        $header['title'] = '注册 HustVote 在线投票';
        $data['error'] = $error;
        $this->load->view('header', $header);
        $this->load->view('register', $data);
        $this->load->view('footer');
    }

    public function email_check($email) {
        if ($this->user_model->isEmailExist($email)) {
            return false;
        } else {
            return true;
        }
    }

    public function register() {
        if (!empty($this->userinfo['uid'])) {
            redirect();
            return;
        }
        $this->form_validation->set_rules('email', '邮箱', 'trim|required|max_length[50]|min_length[3]|valid_email|callback_email_check');
        $this->form_validation->set_rules('nickname', '昵称', 'trim|required|max_length[50]|min_length[1]');
        $this->form_validation->set_rules('password', '密码', 'trim|required|max_length[50]|min_length[6]|matches[password2]');
        if ($this->form_validation->run() == false) {
            $this->_showRegister(validation_errors());
        } else {
            $data = $this->input->post();
            $data = array_map('trim', $data);
            unset($data['password2']);
            $callback = null;
            if ($this->user_model->doReg($data, $callback)) {
                $this->user_model->setLogin($callback);
                
                //发送邮件
                $userinfo = $this->user_model->getUserInfo($callback);
                $state = $this->user_model->sendVerifyEmail($userinfo);
                //var_dump($callback);
                redirect();
                //TODO 注册成功页面
            } else {
                $this->_showRegister($this->errorhandler->getErrorDes($callback));
            }
        }
    }


    public function logout() {
        $this->user_model->setLogout();
        redirect();
    }

    public function verify($uid = null, $token = null) {
        if (empty($uid) || empty($token)) {
            if (empty($this->userinfo['uid'])) {
                redirect('user/login');
                return;
            }
            if ($this->userinfo['is_verified']) {
                $tip = "邮箱已经验证";
            } else {
                $state = $this->user_model->sendVerifyEmail($this->userinfo);
                $tip = $state ? "验证邮件已发送，请注意查收" : "邮件发送失败，请重试";
            }
        } else {
            $state = $this->user_model->verifyToken($uid, $token);
            $tip = $state ? "验证成功" : "无效的链接，请重新申请验证";
        }
        $header['userinfo'] = $this->userinfo;
        $header['title'] = '邮箱验证 HustVote 在线投票';
        $this->load->view('header', $header);
        $this->load->view('tip', array('tip' => $tip));
        $this->load->view('footer');
    }

}
