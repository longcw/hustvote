<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class S_vote extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->load->model('vote_model');
    }

    /**
     * 获取投票列表
     * 需要post page
     * 可选 is_end,is_start, is_completed
     */
    public function getVoteList() {
        $pdata = $this->input->post();
        $page = empty($pdata['page']) ? 0 : $pdata['page'];
        $keys = array(
            'is_end', 'is_start', 'is_completed'
        );
        $limit = elements($pdata, $keys, NULL);
        $limit = array_filter($limit);
        $list = $this->vote_model->getVotesByPage($page, $limit);
        if (empty($list)) {
            $this->setCode(1003);
        } else {
            $this->setCode(1000);
            //$this->setResult($list);
            $this->addResult("votelist", $list);
        }
        $this->reply();
    }

    public function getVoteDetail() {
        $vid = $this->input->post('vid');
        $data = $this->vote_model->getVoteDetailById($vid);
        if (empty($data)) {
            $this->setCode(1004);
        } else {
            $this->setCode(1000);
            $this->setResult($data);
        }
        $this->reply();
    }

    public function getNewVote() {
        $pdata = $this->input->post();
        $keys = array(
            'is_end', 'is_start', 'is_completed'
        );
        $limit = elements($pdata, $keys, NULL);
        $limit = array_filter($limit);
        $ltime = $this->input->post("last_time");
        
        if (!is_numeric($ltime)) {
            $this->setCode(1005);
        } else {
            $data = $this->vote_model->getVotesByPage(0, $limit, 8, $ltime);
            if (empty($data)) {
                $this->setCode(1003);
            } else {
                $this->setCode(1000);
                $this->addResult("votelist", $data);
            }
        }
        $this->reply();
    }
    
    public function getChoiceDetail() {
        $cid = $this->input->post('cid');
        $data = $this->vote_model->getChoiceDetail($cid);
        if(empty($data)) {
            $this->setCode(1005);
        } else {
            $this->setCode(1000);
            $this->setResult($data);
        }
        $this->reply();
    }
}
