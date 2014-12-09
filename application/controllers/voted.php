<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Voted extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vote_model');
//        $this->load->helper('form');
//        $this->load->library('form_validation');
//        $this->setFormMessage();
//        date_default_timezone_set('PRC');
    }
    
    public function result($vid) {
        $data['vote'] = $this->vote_model->getVoteDetailById($vid);
        if(empty($data['vote'])) {
            //TODO 不存在的投票
            return ;
        }
        $data['result'] = $this->vote_model->getVoteResult($vid);
        
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = $data['vote']['content']['title'] . ' 投票结果 --HustVote 在线投票';
        $header['css'] = array();
        $footer['js'] = array('ChartNew', 'result');

        $this->load->view('header', $header);
        $this->load->view('result', $data);
        $this->load->view('footer', $footer);
    }
    
}