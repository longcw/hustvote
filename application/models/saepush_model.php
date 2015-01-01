<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SaePush_model extends CI_Model {

    private $APPID = 21592;

    public function __construct() {
        $this->load->database();
    }

    public function pushMsg($token, $title, $msg, $acts, $extra) {
        $adpns = new SaeADPNS();
        $result = $adpns->push($this->APPID, $token, $title, $msg, $acts, $extra);
        return $result;
    }

    public function pushComment($cid) {
        $this->load->model('comment_model');
        $comment = $this->comment_model->getCommentById($cid);
        if(empty($comment)) {
            return;
        }
        
        
        $token = 'BYhGgDwctw4P';
        $title = 'title';
        $msg = 'hello wolrd';
        $acts = "[\"2,com.hustvote.hustvote,com.hustvote.hustvote.ui.VoteActivity\"]";
        $extra = array(
            'handle_by_app' => '1'
        );

        $adpns = new SaeADPNS();
//appid 是应用的标识，从SAE的推送服务页面申请
//token 是SDK通道标识，从SDK的onPush中获取
        $result = $adpns->push($appid, $token, $title, $msg, $acts, $extra);
        if ($result && is_array($result)) {
            echo '发送成功！';
            var_dump($result);
        } else {
            echo '发送失败。';
            var_dump($apns->errno(), $apns->errmsg());
        }
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
