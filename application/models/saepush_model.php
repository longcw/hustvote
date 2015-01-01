<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SaePush_model extends CI_Model {

    private $APPID = 21592;

    public function __construct() {
        $this->load->database();
    }


    public function pushMsg($token, $title, $msg,
            $acts = "[\"2,com.hustvote.hustvote,com.hustvote.hustvote.ui.NoticeActivity\"]",
            $extra = array('handle_by_app' => '0')) {
        $adpns = new SaeADPNS();
        $result = $adpns->push($this->APPID, $token, $title, $msg, $acts, $extra);
        return $result;
    }

    public function pushComment($cid) {
        $this->load->model('comment_model');
        $comment = $this->comment_model->getCommentById($cid);
        if (empty($comment)) {
            return;
        }
        $saeToken = $this->getSAEToken($comment['to_uid']);
        if(empty($saeToken)) {
            return FALSE;
        }
        $token = $saeToken['saetoken'];
        $title = $comment['from_nickname'] . ' 评论了你';
        $msg = $comment['content'];

        $result = $this->push($token, $title, $msg);
        if ($result && is_array($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function pushUnreadComment($uid) {
        $this->load->model('comment_model');
        $count = $this->comment_model->getUnreadCommentCountByUser($uid);
        if($count <= 0) {
            return;
        }
        $saeToken = $this->getSAEToken($uid);
        if(empty($saeToken)) {
            return;
        }

        $token = $saeToken['saetoken'];
        $title = '新评论';
        $msg = "有 $count 条未读评论，点击查看";

        $this->push($token, $title, $msg);
    }

    public function updateSAEToken($uid, $token) {
        $data = array('uid' => $uid, 'saetoken' => $token);
        $old_token = $this->getSAEToken($uid);
        if (empty($old_token)) {
            $this->db->insert('SAEPushToken', $data);
        } else {
            $this->db->update('SAEPushToken', $data, array('uid' => $uid));
        }
    }

    public function getSAEToken($uid) {
        $this->db->limit(1)->select('saetoken, update_time');
        $query = $this->db->get_where('SAEPushToken', array('uid' => $uid));
        return $query->row_array();
    }

}
