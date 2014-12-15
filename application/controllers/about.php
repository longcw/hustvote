<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class About extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $data['tip'] = "攒劲建设中...";
        
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '我参与的投票 --HustVote 在线投票';
        $header['css'] = array();
        $footer['js'] = array('ChartNew',);

        $this->load->view('header', $header);
        $this->load->view('tip', $data);
        $this->load->view('footer', $footer);
    }

}
