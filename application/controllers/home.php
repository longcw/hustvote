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
        $data['total_page'] = $this->vote_model->getVotePage();
        if(!is_numeric($page) || $page <= 0 || ($page > $data['total_page'] && $page != 1)) {
            echo $this->errorhandler->getErrorDes('ErrorPage');
            return;
        }
        
        $data['votes'] = $this->vote_model->getVotesByPage($page - 1);
        $data['cpage'] = $page;
        $header['userinfo'] = $this->userinfo;
        $header['lastdiv'] = false;
        $header['title'] = '投票大厅 HustVote 在线投票';
        $header['act'] = 'hall';
        $this->load->view('header', $header);
        $this->load->view('hall', $data);
        $this->load->view('footer');
    }

}
