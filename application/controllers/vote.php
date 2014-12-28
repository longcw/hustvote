<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vote extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vote_model');
        $this->load->model('comment_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->setFormMessage();
        date_default_timezone_set('PRC');
    }

    private function setFormMessage() {
        $this->form_validation->set_message('required', '请填写 %s');
        $this->form_validation->set_message('valid_email', '请填写正确的 %s');
        $this->form_validation->set_message('max_length', '%s 不能超过%s位');
        $this->form_validation->set_message('min_length', '%s 不能少于%s位');
        $this->form_validation->set_message('matches', '两次输入的 %s 不同');
        $this->form_validation->set_message('email_check', '%s已经被注册');
        $this->form_validation->set_message('is_natural_no_zero', '%s必须是一个大于0的整数');
        $this->form_validation->set_message('less_than', '%s必须小于选项数目');
    }

    private function _showStartVote($count = 2, $error = '') {
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '发起新投票 HustVote 在线投票';
        $header ['act'] = 'hall';
        $header['css'] = array('jquery.datetimepicker', 'umeditor/themes/default/css/umeditor');
        $footer['js'] = array('jquery.datetimepicker', 'umeditor/umeditor.config', 'umeditor/umeditor.min', 'startvote',);
        $data['error'] = $error;
        $data['count'] = $count;

        $this->load->view('header', $header);
        $this->load->view('start_vote', $data);
        $this->load->view('footer', $footer);
    }

    public function choice() {
        $cid = $this->input->get('cid');
        $choice = $this->vote_model->getChoiceDetail($cid);
        $choice['status'] = empty($choice) ? false : TRUE;
        if (empty($choice['choice_intro'])) {
            $choice['choice_intro'] = $choice['choice_name'];
        }
        $this->output->set_output(json_encode($choice));
    }

    public function start() {
        $uid = $this->userinfo['uid'];
        if (!$this->right_model->hasRight('AddNewVote', $uid)) {
            //TODO no right
            redirect('user/login');
            return;
        }
        $data = $this->input->post();
        $choice_count = !empty($data) ? count($data['choice']) : 2;
        $this->form_validation->set_rules('title', '投票标题', 'trim|required');
        $this->form_validation->set_rules('choice_max', '投票限制', 'trim|required|is_natural_no_zero|less_than[' . $choice_count . ']');
        $this->form_validation->set_rules('intro', '投票描述', 'trim|required');
        $this->form_validation->set_rules('start_time', '开始时间', 'trim');
        $this->form_validation->set_rules('end_time', '结束时间', 'trim');
        $this->form_validation->set_rules('choice[]', '选项', 'trim|required');
        $this->form_validation->set_rules('detail[]', '选项详情', 'trim');
        $this->form_validation->set_rules('start_select', '开始时间设置', 'required');
        $this->form_validation->set_rules('end_select', '结束时间设置', 'required');

        if ($this->form_validation->run() == false) {
            $this->_showStartVote($choice_count);
        } else {
            //立即开始
            if ($data['start_select'] == 0 || $data['start_time'] == 0) {
                $data['start_time'] = time();
            } else {
                $data['start_time'] = strtotime($data['start_time']);
            }
            //长期有效
            if ($data['end_select'] == 0 || $data['end_time'] == 0) {
                $data['end_time'] = -1;
            } else {
                $data['end_time'] = strtotime($data['end_time']);
            }
            unset($data['end_select']);
            unset($data['start_select']);
            unset($data['editorValue']);
            $data['uid'] = $uid;
            $data['title'] = htmlspecialchars($data['title']);
            $data['choice'] = array_map('htmlspecialchars', $data['choice']);
            $callback = null;
            $this->vote_model->addNewVote($data, $callback);

            redirect("vote/start_step_2/$callback");
        }
    }

    public function start_step_2($start_voteid) {
        $uid = $this->userinfo['uid'];
        if (!$this->right_model->hasRight('EditVote', $uid, $start_voteid)) {
            //TODO no right
            echo $this->errorhandler->getErrorDes('NoRight');
            return;
        }

        $votedata = array(
            'start_voteid' => $start_voteid
        );
        $this->session->set_userdata($votedata);

        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '完成新投票 HustVote 在线投票';
        $header ['act'] = 'hall';
        $header['css'] = array('icheck-skins/square/blue');
        $footer['js'] = array('fingerprint', 'icheck', 'start_step_2');

        $this->load->view('header', $header);
        $this->load->view('start_vote_2', $votedata);
        $this->load->view('footer', $footer);
    }

    public function doSetLimit($start_voteid) {
        $uid = $this->userinfo['uid'];
        if (!$this->right_model->hasRight('EditVote', $uid, $start_voteid)) {
            //TODO no right
            echo $this->errorhandler->getErrorDes('NoRight');
            return;
        }

        $pdata = $this->input->post();
        $this->load->helper('array');
        $data = elements(array(
            'code_need', 'ip_address', 'captcha_need', 'email_need', 'email_limit', 'email_area', 'cycle_time'
                ), $pdata);
        if ($data['code_need']) {
            $data['ip_address'] = 0;
            $data['captcha_need'] = 0;
            $data['email_need'] = 0;
        }
        if (empty($data['email_area'])) {
            $data['email_limit'] = false;
        }

        $data['start_voteid'] = $start_voteid;
        $this->vote_model->setVoteLimit($data);
        $this->vote_model->setVoteCompleted($start_voteid);
        // TODO 发起投票成功
        redirect('vote/join/' . $start_voteid);
    }

    public function join($vid) {
        $code = $this->input->get('code');
        $callback = array('type' => 'none');
        //$this->_hasRightToVote($callback, $id, array(), $code);
        $ident = $this->getUserIdentification();
        $this->vote_model->hasRightToVote($callback, $vid, $ident, $code);
        $data['error'] = $callback;
        if ($callback['type'] == 'errorvote' && empty($callback['vtime'])) {
            //不存在的投票
            echo $this->errorhandler->getErrorDes('ErrorVote');
            return;
        }

        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '参与投票 HustVote 在线投票';
        $header ['act'] = 'hall';
        $header['css'] = array('umeditor/themes/default/css/umeditor', 'icheck-skins/square/green');
        $footer['js'] = array('umeditor/umeditor.config', 'umeditor/umeditor.min', 'icheck', 'jquery.pin', 'fingerprint', 'joinvote');
        $data['detail'] = $this->vote_model->getVoteDetailById($vid);
        $data['comment'] = $this->comment_model->getCommentByVote($vid);
        $data['code'] = $code;
        if (empty($data['detail']) || !$data['detail']['content']['is_completed']) {
            echo $this->errorhandler->getErrorDes('ErrorVote');
            return;
        }
        $data['voteuser'] = $this->user_model->getUserInfo($data['detail']['content']['uid']);
        $this->load->view('header', $header);
        $this->load->view('join_vote', $data);
        $this->load->view('footer', $footer);
    }

    public function doAddComment() {
        $out = array('status' => false, 'msg' => 'null');
        if (!$this->isLogin()) {
            $out['msg'] = '请先登录';
        } else {
            $content = $this->input->post('content');
            $vid = $this->input->post('vid');
            $to_uid = $this->input->post('to_uid');
            $uid = $this->userinfo['uid'];
            $vtitle = $this->vote_model->getVoteTitle($vid);
            if (empty($vtitle)) {
                echo 'error vid';
                return;
            }
            $data = array(
                'from_uid' => $uid,
                'to_uid' => $to_uid,
                'vid' => $vid,
                'content' => htmlspecialchars($content)
            );
            if (($cid = $this->comment_model->addComment($data))) {
                $out['status'] = true;
                $row = $this->comment_model->getCommentById($cid);
                $str = '<li>
                        <div class="media-body">
                            <h4 class="comment-author">' . $row['from_nickname'] . ' <small> on ' . date('Y-m-d H:i:s', $row['create_time']) .
                        '<a href="#" class="comment-reply" value="' . $row['from_uid'] . '" nickname ="' . $row['from_nickname'] . '">回复</a></small></h4>
                            <div class="comment-content">' . $row['content'] .
                        '</div></div></li>';
                $out['str'] = $str;
            } else {
                $out['msg'] = '评论失败';
            }
        }
        $this->output->set_content_type('json')->set_output(json_encode($out));
    }

    public function doJoinVote() {
        $pdata = $this->input->post();
        $code = empty($pdata['code']) ? null : $pdata['code'];
        $choice_max = $this->vote_model->getVoteChoiceMax($pdata['start_voteid']);
        if (count($pdata['choice']) > $choice_max) {
            echo $this->errorhandler->getErrorDes('ErrorVote');
            return;
        }
        $id['captcha'] = $pdata['captcha'];
        $id['fingerprint'] = $pdata['fingerprint'];
        $id['code'] = $code;
        $id = array_merge($this->getUserIdentification(), $id);
        $callback = null;
        if (!$this->vote_model->hasRightToVote($callback, $pdata['start_voteid'], $id, $code)) {
            $url = base_url('vote/join/' . $pdata['start_voteid']);
            $tip = "投票失败，错误代码:$callback[type] <a href='$url'>返回</a>";
        } else {
            $id['via'] = 'web';
            $logid = $this->vote_model->addVoteLog($pdata['start_voteid'], $id);
            $this->vote_model->setCodeUsed($pdata['start_voteid'], $code, $logid);
            $this->vote_model->addaVote($pdata['start_voteid'], $pdata['choice'], $logid);
            $url = base_url('vote/result/' . $pdata['start_voteid']);
            $tip = "投票成功 <a href='$url'>查看投票结果</a>";
        }

        $header['userinfo'] = $this->userinfo;
        $header['title'] = '提示 HustVote 在线投票';
        $this->load->view('header', $header);
        $this->load->view('tip', array('tip' => $tip));
        $this->load->view('footer');
    }

    public function result($vid) {
        $data['vote'] = $this->vote_model->getVoteTitle($vid);
        if (empty($data['vote'])) {
            //TODO 不存在的投票
            return;
        }

        $data['result'] = $this->vote_model->getVoteResult($vid);
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = $data['vote']['title'] . ' 投票结果 --HustVote 在线投票';
        $header['css'] = array('icheck-skins/flat/blue');
        $footer['js'] = array('ChartNew', 'icheck', 'result');

        $this->load->view('header', $header);
        $this->load->view('result', $data);
        $this->load->view('footer', $footer);
    }

    /**
     * 发起的投票
     */
    public function started() {
        $uid = $this->userinfo['uid'];
        if (empty($uid)) {
            return;
        }
        $data['votelog'] = $this->vote_model->getUserStartVote($uid);

        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '我发起的投票 --HustVote 在线投票';
//        $header['css'] = array();
//        $footer['js'] = array();

        $this->load->view('header', $header);
        $this->load->view('started', $data);
        $this->load->view('footer');
    }

    public function mycode($vid) {
        $uid = $this->userinfo['uid'];
        if (empty($uid)) {
            redirect('user/login');
            return;
        }
        if (!$this->right_model->hasRight('EditVote', $uid, $vid)) {
            echo "no right";
            return;
        }
        $data['codelog'] = $this->vote_model->getCodeByVote($vid);
        $data['votetitle'] = $this->vote_model->getVoteTitle($vid);
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '邀请码管理 ' . $data['votetitle']['title'] . ' --HustVote 在线投票';
//        $header['css'] = array('icheck-skins/flat/blue');
        $footer['js'] = array('mycode');

        $this->load->view('header', $header);
        $this->load->view('mycode', $data);
        $this->load->view('footer', $footer);
    }

    public function getCodeLog($vid, $code) {
        $uid = $this->userinfo['uid'];
        if (empty($uid)) {
            redirect('user/login');
            return;
        }
        if (!$this->right_model->hasRight('EditVote', $uid, $vid)) {
            echo "no right";
            return;
        }

        $data = $this->vote_model->getVoteLogByCode($vid, $code);
        $out = array('status' => false);
        if (!empty($data)) {
            $out['status'] = true;
            $votelog = $data['votelog'];

            $logstr = "<b>【 $code 】投票者信息</b>\n"
                    . "邮箱：$votelog[email]\n"
                    . "邮箱验证：" . ($votelog['is_verified'] ? '已通过' : '未通过')
                    . "\n投票方式：$votelog[via]\n"
                    . "投票时间：" . date('Y-m-d H:i:s', $votelog['vote_time']);
            $out['log'] = nl2br($logstr);

            $selectstr = "<b>选择</b>\n";
            foreach ($data['select'] as $row) {
                $selectstr .= $row['choice_name'] . "\n";
            }
            $out['select'] = nl2br($selectstr);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function addCode($vid) {
        $uid = $this->userinfo['uid'];
        if (empty($uid)) {
            redirect('user/login');
            return;
        }
        if (!$this->right_model->hasRight('EditVote', $uid, $vid)) {
            echo "no right";
            return;
        }

        $count = $this->input->post('count');
        $this->vote_model->addCode($vid, $uid, $count);
        redirect("vote/mycode/$vid");
    }

    /**
     * 参与的投票
     */
    public function joined() {
        $identification = $this->getUserIdentification();
        $data['votelog'] = $this->vote_model->getUserVoteLog($identification);

        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '我参与的投票 --HustVote 在线投票';
//        $header['css'] = array();
//        $footer['js'] = array();

        $this->load->view('header', $header);
        $this->load->view('joined', $data);
        $this->load->view('footer');
    }

}
