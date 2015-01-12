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
            'is_end', 'is_start', 'is_completed', 'is_hot', 'offset'
        );
        $limit = elements($keys, $pdata, NULL);
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
            $code = $this->input->post('code');
            $id = $this->getUserIdentification();
            $id['IMEI'] = $this->input->post('IMEI');
            $callback = null;
            $this->vote_model->hasRightToVote($callback, $vid, $id, $code);
            $data['logtype'] = $callback['type'];
            $data['logmsg'] = $this->_getLogtypeMsg($callback);

            $this->setCode(1000);
            $this->setResult($data);
        }
        $this->reply();
    }

    private function _getLogtypeMsg($callback) {
        $msg = 'none';
        if (empty($callback)) {
            return $msg;
        }
        switch ($callback['type']) {
            default :
            case 'none' :
            case 'captcha_need':
                $msg = 'none';
                break;

            case 'errorvote':
                //投票已经开始或结束，或者错误的投票id
                $msg = '投票' . $callback['vtime']['start_time'] > time() ? '还未开始' : '已经结束';
                break;
            case 'code_need':
                $msg = '参与本投票需要邀请码';
                break;
            case 'email_need':
                $msg = '参与本投票须要验证邮箱';
                break;
            case 'email_limit':
                $msg = '本投票只允许邮箱域名为 ' . $callback['email_area'] . ' 的用户参与';
                break;
            case 'cycle_time':
                $msg = '您已经在 ' . date('Y年m月d日 H时i分', $callback['votetime']) . ' 参与投票';
                break;
        }
        return $msg;
    }

    public function getNewVote() {
        $pdata = $this->input->post();
        $keys = array(
            'is_end', 'is_start', 'is_completed', 'is_hot'
        );
        $limit = elements($keys, $pdata, NULL);
        $limit = array_filter($limit);
        $ltime = $this->input->post("last_time");

        if (isset($limit['is_hot']) && $limit['is_hot'] == 1) {
            $limit = 0;
        }

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
        if (empty($data)) {
            $this->setCode(1005);
        } else {
            $this->setCode(1000);
            $this->setResult($data);
        }
        $this->reply();
    }

    /**
     * 获取用户的投票信息(未使用)
     */
    public function getVoteLog() {
        $pdata = $this->input->post();
        $state = $this->_isValid($pdata);
        if ($state != 1000) {
            $this->setCode($state);
        } else {
            $vid = $pdata['vid'];
            $id = $this->getUserIdentification();
            $id['IMEI'] = $pdata['IMEI'];
            $log = $this->vote_model->getLastVoteLog($vid, $id);
//            if(empty($log)) {
//                $this->setCode($code);
//            }
            $this->setCode(1000);
            $this->setResult($log);
        }

        $this->reply();
    }

    public function doJoinVote() {
        $pdata = $this->input->post();
//        var_dump($pdata);
//        var_dump($this->session->all_userdata());
//        return;
        $code = $this->input->post('code');
        //验证登录
        $state = $this->_isValid($pdata);
        if ($state != 1000) {
            $this->setCode($state);
        } else {
            $vid = $pdata['vid'];
            $id = $this->getUserIdentification();
            $id['code'] = $code;
            $id['IMEI'] = $pdata['IMEI'];
            $id['captcha'] = empty($pdata['captcha']) ? "" : $pdata['captcha'];
            $callback = null;
            if (!$this->vote_model->hasRightToVote($callback, $vid, $id, $code)) {
                //投票失败
                $this->setCode(1007);
                $msg = $callback['type'] == 'captcha_need' ? '需要验证码' : $this->_getLogtypeMsg($callback);
                $this->setMessage('投票失败：' . $msg);
            } else {
                $id['via'] = 'android';
                $logid = $this->vote_model->addVoteLog($vid, $id);
                $this->vote_model->setCodeUsed($vid, $code, $logid);
                $choices = explode(':', $pdata['choice']);
                $choices = array_filter($choices);
                $this->vote_model->addaVote($vid, $choices, $logid);
                $this->setCode(1000);
            }
        }

        $this->reply();
    }

    /**
     * 是否合法
     * @param array $data 包括登录信息，IMEI
     * @return int 错误代码
     */
    private function _isValid($data) {
        if (empty($data['IMEI'])) {
            return 1005;
        }

        $callback = null;
        if ($this->user_model->doLogin($data, $callback)) {
            $this->user_model->setLogin($callback);
        } else {
            //登录失败
            $this->user_model->setLogout();
            return 1006;
        }
        return 1000;
    }

    public function getVoteResult() {
        $vid = $this->input->post('vid');
        $result = $this->vote_model->getVoteResult($vid);
        if (empty($result)) {
            $this->setCode(1004);
        } else {
            $this->setCode(1000);
            $title = $this->vote_model->getVoteTitle($vid);
            $this->addResult("resultdata", $result);
            $this->addResult("title", $title['title']);
        }
        $this->reply();
    }

    public function captcha() {
        $this->load->library('PhptextClass');
        $code = rand(10000, 99999);
        $this->session->set_userdata('captcha_code', $code);

        $this->output->set_content_type('jpeg');
        $this->phptextclass->phpcaptcha($code, '#0000CC', '#fff', 140, 50, 10, 25);
    }

    public function getCommentByVote() {
        $vid = $this->input->post('vid');
        $page = $this->input->post('page');
        //客户端刷新评论列表要更新offset
        $offset = $this->input->post('offset');
        $result = $this->comment_model->getCommentByVote($vid, $page, 0, $offset);
        if (empty($result)) {
            $this->setCode(1003);
        } else {
            $this->setCode(1000);
            $this->addResult("commentlist", $result);
        }
        $this->reply();
    }

    public function getNewCommentByVote() {
        $vid = $this->input->post('vid');
        $ltime = $this->input->post('last_time');
        $result = $this->comment_model->getCommentByVote($vid, 0, $ltime);
        if (empty($result)) {
            $this->setCode(1003);
        } else {
            $this->setCode(1000);
            $this->addResult("commentlist", $result);
        }
        $this->reply();
    }

    public function addComment() {
        if (!$this->isLogin()) {
            $this->setCode(1002);
            $this->reply();
            return;
        }

        $pdata = $this->input->post();
        //$data from_uid, to_uid, vid, content
        $data = array_filter($pdata);
        $vote = $this->vote_model->getVoteTitle($data['vid']);
        if (empty($vote)) {
            $this->setCode(1004);
        } else {
            if (!isset($data['to_uid']) || $data['to_uid'] <= 0) {
                $data['to_uid'] = $vote['uid'];
            }

            if (count($data) != 4) {
                $this->setCode(1005);
            } else {
                $ctime = time();
                $cid = $this->comment_model->addComment($data, $ctime);
                $new = $this->comment_model->getCommentById($cid);
                $this->setResult($new);
                $this->setCode(1000);
            }
        }
        $this->reply();
    }

}
