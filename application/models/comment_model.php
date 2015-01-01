<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comment_model extends CI_Model {
    private static $PAGE_COUNT = 20;

    public function __construct() {
        $this->load->database();
    }

    /**
     * 添加评论
     * @param array $data from_uid, to_uid, vid, content
     */
    public function addComment($data) {
        if($data['from_uid'] == $data['to_uid']) {
            $data['is_read'] = 1;
        }
        $data['create_time'] = time();
        $this->db->insert('Comment', $data);
        return $this->db->insert_id();
    }

    /**
     * 获取投票评论
     * @param type $vid
     * @return type
     */
    public function getCommentByVote($vid) {
        $this->db->select('nickname as from_nickname, Comment.*')
                ->join('User', 'Comment.from_uid=User.uid');
        $this->db->where('vid', $vid)->order_by('create_time desc');
        $query = $this->db->get('Comment');
        return $query->result_array();
    }

    public function getCommentById($cid) {
        $this->db->select('nickname as from_nickname, Comment.*')
                ->join('User', 'Comment.from_uid=User.uid')->limit(1);
        $this->db->where('cid', $cid);
        $query = $this->db->get('Comment');
        return $query->row_array();
    }

    /**
     * 获取用户评论
     * @param type $uid
     */
    public function getCommentByUser($uid, $page = 0, $ltime = 0) {
        
        if($ltime <= 0) {
            $this->db->limit(self::$PAGE_COUNT, self::$PAGE_COUNT * $page);
        } else {
            $this->db->where('create_time >', $ltime);
        }
        $this->db->select('nickname as from_nickname, Comment.*, StartVote.title')
                ->join('User', 'Comment.from_uid=User.uid')
                ->join('StartVote', 'StartVote.start_voteid=vid');
        $this->db->where('to_uid', $uid)->order_by('create_time desc');
        $query = $this->db->get('Comment');
        return $query->result_array();
    }
    
    public function getUnreadCommentCountByUser($uid) {
        $this->db->where('is_read', 0)->select('COUNT(cid) as count');
        $this->db->where('to_uid', $uid)->order_by('create_time desc');
        $query = $this->db->get('Comment');
        $row = $query->row_array();
        return $row['count'];
    }

    public function setCommentRead($cid) {
        $this->db->limit(1);
        $this->db->update('Comment', array('is_read' => 1), array('cid' => $cid));
    }

    public function setCommentReadByUser($uid) {
        $this->db->update('Comment', array('is_read' => 1), array('to_uid' => $uid));
    }

}
