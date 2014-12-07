<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	protected $userinfo = array('uid'=>null);

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
                $this->load->model('right_model');
		$uid = $this->session->userdata('uid');

		if (!empty($uid)){
			$this->userinfo = $this->user_model->getUserInfo($uid);
		}
	}
}