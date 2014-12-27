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
        $header ['title'] = '建设中 --HustVote 在线投票';

        $this->load->view('header', $header);
        $this->load->view('tip', $data);
        $this->load->view('footer');
    }

}
