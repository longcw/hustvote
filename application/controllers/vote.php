<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Vote extends MY_Controller {
	public function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'form' );
		$this->load->library ( 'form_validation' );
		$this->setFormMessage ();
	}
	
	private function setFormMessage() {
		$this->form_validation->set_message ( 'required', '请填写 %s' );
		$this->form_validation->set_message ( 'valid_email', '请填写正确的 %s' );
		$this->form_validation->set_message ( 'max_length', '%s 不能超过%s位' );
		$this->form_validation->set_message ( 'min_length', '%s 不能少于%s位' );
		$this->form_validation->set_message ( 'matches', '两次输入的 %s 不同' );
		$this->form_validation->set_message ( 'email_check', '%s已经被注册' );
	}
	
	private function _showStartVote($error) {
		$header ['userinfo'] = $this->userinfo;
		$header ['title'] = 'HustVote 在线投票';
		$header ['act'] = 'hall';
		$data['error'] = $error;
		$this->load->view ( 'header', $header );
		$this->load->view ( 'new_vote', $data);
		$this->load->view ( 'footer' );
	}
	
	public function start() {
		$this->form_validation->set_rules ( 'title', '投票标题', 'trim|required' );
		$this->form_validation->set_rules ( 'choice_count', '投票限制', 'trim|required' );
		$this->form_validation->set_rules ( 'intro', '投票描述', 'trim|required' );
		if ($this->form_validation->run () == false) {
			$this->_showStartVote ( validation_errors () );
		} else {
			$data = $this->input->post ();
			$data = array_map ( 'trim', $data );
			$callback = null;
			// TODO 发起投票
		}
	}
}