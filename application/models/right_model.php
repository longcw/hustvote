<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Right_Model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 是否有权进行
     * @param string $right
     *  AddNewVote
     *  EditVote
     *  DeleteComment
     * @param int $uid
     * @param mix $target
     */
    public function hasRight($right, $uid, $target = NULL) {
        $has = false;
        switch (strtolower($right)) {
            case 'addnewvote':
                $has = (!empty($uid) && $uid > 0);
                break;

            case 'editvote':
                $has = $this->hasEditVote($uid, $target);
                break;
        }

        return $has;
    }

    private function hasEditVote($uid, $voteid) {
        if(!is_numeric($voteid)) {
            return false;
        }
        if ($this->getUserGroupId($uid) > 1) {
            //管理员
            return true;
        }
        $query = $this->db->select('uid')->limit(1)->get_where('StartVote', array('start_voteid'=>$voteid));
        $row = $query->row_array();
        if(empty($row)) {
            return FALSE;
        }
        return $row['uid'] == $uid;
    }

    public function getUserGroupId($uid) {
        if (!is_numeric($uid)) {
            return -1;
        }
        $query = $this->db->limit(1)->get_where('User', array('uid' => $uid));
        if (($row = $query->row_array())) {
            return $row['groupid'];
        } else {
            return -1;
        }
    }

}
