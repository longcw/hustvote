<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function captcha() {
        $this->load->library('PhptextClass');
        $code = $this->phptextclass->random();
        $this->session->set_userdata('captcha_code', $code);
        $this->output->set_content_type('jpeg');
        $this->phptextclass->phpcaptcha($code, '#0000CC', '#fff', 140, 50, 10, 25);
    }
    
    public function qrcode($content) {
        $this->load->library('PhpQrcode');
        $this->output->set_content_type('png');
        $content = urldecode($content);
        $this->phpqrcode->png($content);
    }

    public function captcha_test() {
        $captcha = $this->input->get('captcha');
        $origin = $this->session->userdata('captcha_code');
        if (!empty($captcha) && $captcha == $origin) {
            $status = true;
        }else {
            $status = false;
        }
        $this->output->set_output(json_encode($status));
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

        $limit = array('is_completed' => 1);
        $data['total_page'] = $this->vote_model->getVotePage($limit);
        if (!is_numeric($page) || $page <= 0 || ($page > $data['total_page'] && $page != 1)) {
            echo $this->errorhandler->getErrorDes('ErrorPage');
            return;
        }

        $data['votes'] = $this->vote_model->getVotesByPage($page - 1, $limit);
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
