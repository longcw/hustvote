<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		if(!empty($this->userinfo)) {
			redirect();
			return;
		}
		$this->form_validation->set_rules('email', '邮箱', 'trim|required');
		$this->form_validation->set_rules('password', '密码', 'trim|required');
		if($this->form_validation->run() == false) {
			$this->_showLogin(validation_errors());
		} else {
			$data = $this->input->post();
			$data = array_map('trim', $data);
			$callback = null;
			if($this->user_model->doLogin($data, $callback)) {
				$this->_setLogin($callback);
				redirect();
			}else {
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
		if($this->user_model->isEmailExist($email)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function register() {
		if(!empty($this->userinfo)) {
			redirect();
			return;
		}
		$this->form_validation->set_rules('email', '邮箱', 'trim|required|max_length[50]|min_length[3]|valid_email|callback_email_check');
		$this->form_validation->set_rules('nickname', '昵称', 'trim|required|max_length[50]|min_length[1]');
		$this->form_validation->set_rules('password', '密码', 'trim|required|max_length[50]|min_length[6]|matches[password2]');
		if($this->form_validation->run() == false) {
			$this->_showRegister(validation_errors());
		} else {
			$data = $this->input->post();
			$data = array_map('trim', $data);
			unset($data['password2']);
			$callback = null;
			if($this->user_model->doReg($data, $callback)) {
				$this->_setLogin($callback);
				
				//var_dump($callback);
				redirect();
				//TODO 注册成功页面
			}else {
				$this->_showRegister($this->errorhandler->getErrorDes($callback));
			}
		}
	}
	
	private function _setLogin($uid) {
		$this->session->set_userdata('uid', $uid);
	}
	
	public function logout() {
		$this->session->unset_userdata('uid');
		redirect();
	}
}