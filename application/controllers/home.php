<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $header['userinfo'] = $this->userinfo;
        $header['lastdiv'] = false;
        $header['title'] = '首页 HustVote 在线投票';
        $header['act'] = 'home';
        $this->load->view('header', $header);
        $this->load->view('home');
        $this->load->view('footer');
    }

    public function hall($page = 1) {
        $this->load->model('vote_model');
        $data['votes'] = $this->vote_model->getVotesByPage($page - 1);
        //var_dump($data);exit;
        $header['userinfo'] = $this->userinfo;
        $header['lastdiv'] = false;
        $header['title'] = '投票大厅 HustVote 在线投票';
        $header['act'] = 'hall';
        $this->load->view('header', $header);
        $this->load->view('hall', $data);
        $this->load->view('footer');
    }

}
