<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$header['userinfo'] = $this->userinfo;
		$header['lastdiv'] = false;
		$header['title'] = 'HustVote 在线投票';
		$header['act'] = 'home';
		$this->load->view('header', $header);
		$this->load->view('home');
		$this->load->view('footer');
	}
	
	public function hall() {
		$header['userinfo'] = $this->userinfo;
		$header['lastdiv'] = false;
		$header['title'] = 'HustVote 在线投票';
		$header['act'] = 'hall';
		$this->load->view('header', $header);
		$this->load->view('hall');
		$this->load->view('footer');
	}
}