<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	protected $userinfo = array();

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$uid = $this->session->userdata('uid');

		if (!empty($uid)){
			$this->userinfo = $this->user_model->getUserInfo($uid);
		}
	}
}