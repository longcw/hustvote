<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vote_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 添加新的投票
     * @param array $data
     * 
     * 'uid'
     * 'title', '投票标题'
      'choice_max', '投票限制'
      'intro', '投票描述'
      'start_time', '开始时间'
      'end_time', '结束时间'
      'choice[]', '选项'
      'detail[]', '选项详情'
     * 
     * @param string|int $callback 返回id
     * @return boolean 
     */
    public function addNewVote($data, &$callback) {
        $choices = $data['choice'];
        $details = $data['detail'];
        unset($data['choice']);
        unset($data['detail']);

        $summary = $this->getSummary($data['intro']);
        $data = array_merge($data, $summary);
        $data['create_time'] = time();

        //插入startvote
        $this->db->insert('StartVote', $data);
        $id = $this->db->insert_id();

        $this->addChoice($choices, $details, $id);
        $this->setVoteLimit(array('start_voteid'=>$id));
        $callback = $id;
        return true;
    }

    /**
     * 设置投票限制
     * @param array $data
     *  包含start_voteid
     *  code_need','ip_address','captcha_need','email_need','email_limit','email_area','cycle_time'
     * @return int
     */
    public function setVoteLimit($data) {
        $where['start_voteid'] = $data['start_voteid'];
        $query = $this->db->limit(1)->get_where('VoteLimit', $where);
        $limit = $query->row_array();
        if (!empty($limit)) {
            //已经存在
            $this->db->where('limitid', $limit['limitid']);
            $this->db->update('VoteLimit', $data);
        } else {
            $this->db->insert('VoteLimit', $data);
        }

        return $this->db->affected_rows();
    }

    /**
     * 获取投票限制
     * @param int $id
     * @return array
     */
    public function getVoteLimit($id) {
        $limit = array(
            'ip_address' => 0,
            'captcha_need' => 0,
            'cycle_time' => 0,
            'code_need' => 0,
            'email_need' => 0
        );
        $where['start_voteid'] = $id;
        $query = $this->db->limit(1)->get_where('VoteLimit', $where);
        return array_merge($limit, $query->row_array());
    }

    /**
     * 编辑投票
     * @param array $data 
     * @param int $id
     */
    public function editVote($data, $id) {
        $this->deleteChoice($id);
        $this->addChoice($data['choice'], $data['detail'], $id);
        unset($data['choice']);
        unset($data['detail']);

        $summary = $this->getSummary($data['intro']);
        $data = array_merge($data, $summary);

        //更新startvote
        $this->db->where('start_voteid', $id);
        $this->db->update('StartVote', $data);
    }
    
    /**
     * 获取选项详情
     * @param type $cid
     * @return type
     */
    public function getChoiceDetail($cid) {
        if(!is_numeric($cid)) {
            return array();
        }
        $query = $this->db->get_where('Choice', array('choiceid'=>$cid));
        return $query->row_array();
    }

    /**
     * 获取摘要
     * @param string $content
     * @return array
     */
    private function getSummary($content, $long = 200, $short = 50) {
        if (empty($content)) {
            return array();
        }

        $data = array();

        $images = array();
        preg_match('/<img.*src="(.*)"\s*.*>/iU', $content, $images);
        if (isset($images[1])) {
            $data['image'] = $images[1];
            $len = $short;
        } else {
            $data['image'] = "";
            $len = $long;
        }

        $data['summary'] = mb_substr(strip_tags($content), 0, $len, 'UTF-8');

        return $data;
    }

    /**
     * 删除所有选项
     * @param int $id
     */
    private function deleteChoice($id) {
        $this->db->where('start_voteid', $id);
        $this->db->delete('StartVote');
    }

    /**
     * 添加选项
     * @param array $choices
     * @param array $details
     * @param int $id
     */
    private function addChoice($choices, $details, $id) {
        $choices_count = count($choices);
        $choices_data = array();
        for ($i = 0; $i < $choices_count; $i++) {
            $choices_data[] = array(
                'start_voteid' => $id,
                'choice_name' => $choices[$i],
                'choice_intro' => $details[$i],
            );
        }
        $this->db->insert_batch('Choice', $choices_data);
    }

    /**
     * 按页获取投票
     * @param int $page
     * @param array $limit
     *  支持 is_end, is_start
     * 
     * @param int $count
     * @return array
     */
    public function getVotesByPage($page = 0, $limit = array(), $count = 8, $getcount = false) {
        $ctime = time();
        if (isset($limit['is_end'])) {
            $op = $limit['is_end'] ? '<' : '>=';
            $this->db->where('end_time' . $op, $ctime);
        }

        if (isset($limit['is_start'])) {
            $op = $limit['is_start'] ? '<' : '>=';
            $this->db->where('end_time' . $op, $ctime);
        }
        //获取页数
        if ($getcount) {
            $query = $this->db->get('StartVote');
            return $query->num_rows();
        }

        $this->db->order_by('create_time desc');

        $offset = $page * $count;
        $query = $this->db->get('StartVote', $count, $offset);
        return $query->result_array();
    }

    public function getVotePage($limit = array(), $count = 8) {
        $total = $this->getVotesByPage(0, $limit, $count, true);
        return ceil($total / 8);
    }

    public function getVoteDetailById($id) {
        if (empty($id) || !is_numeric($id)) {
            return array();
        }
        $data = array();
        
        $data['choices'] = $this->getVoteChoices($id);

        $content = $this->db->get_where('StartVote', array('start_voteid' => $id), 1);
        $data['content'] = $content->row_array();

        $data['limit'] = $this->getVoteLimit($id);

        return $data;
    }
    
    /**
     * 获取所有选项（不包括选项详情）
     * @param int $id
     * @return array
     */
    public function getVoteChoices($id) {
        $this->db->select('choiceid, start_voteid, choice_name');
        $choices = $this->db->get_where('Choice', array('start_voteid' => $id));
        return $choices->result_array();
    }


    /**
     * 最多可选几项
     * @param int $id
     * @return int
     */
    public function getVoteChoiceMax($id) {
        $this->db->limit(1);
        $this->db->select('choice_max');
        $query = $this->db->get_where('StartVote', array('start_voteid'=>$id));
        $row = $query->row_array();
        return empty($row) ? 0 : $row['choice_max'];
    }
    
    /**
     * 获取投票开始时间和结束时间
     * @param type $id
     * @return type
     */
    public function getVoteTime($id) {
        $this->db->limit(1);
        $this->db->select('start_time,end_time');
        $query = $this->db->get_where('StartVote', array('start_voteid'=>$id));
        $row = $query->row_array();
        return $row;
    }

    /**
     * 获取邀请码信息
     * @param string $code
     * @return array
     */
    public function getCodeInfo($code) {
        if (empty($code)) {
            return array();
        }
        $query = $this->db->get_where('Code', array('code' => $code));
        return $query->row_array();
    }

    /**
     * 获取最近的一条投票记录
     * @param int $voteid
     * @param array $id
     * @return array
     */
    public function getLastVoteLog($voteid, $id) {
        if (!empty($id['openid'])) {
            $id['openid'] = $id['thirdtype'] . ':' . $id['openid'];
            unset($id['thirdtype']);
        }

        $qstr = 'SELECT * FROM VoteLog WHERE start_voteid=? ';
        $data = array($voteid);

        $idstr = 'AND (';
        $id = array_filter($id);
        $i = 0;
        $count = count($id);
        foreach ($id as $key => $value) {
            $i++;
            $idstr .= "$key=?";
            array_push($data, $value);
            if ($i < $count) {
                $idstr .= ' OR ';
            }
        }
        $idstr .= ')';
        $qstr .= $idstr;
        $qstr .= " ORDER BY vote_time DESC LIMIT 1";
        $query = $this->db->query($qstr, $data);
        return $query->row_array();
    }
    
    /**
     * 参与投票
     * @param int $start_voteid
     * @param array $choices 选择的编号
     * @param int $logid
     */
    public function addaVote($start_voteid, $choices, $logid) {
        $tmp = array(
            'start_voteid' => $start_voteid,
            'logid' => $logid,
            'createtime' => time(),
        );
        $data = array();
        foreach ($choices as $choice) {
            $tmp['choiceid'] = $choice;
            array_push($data, $tmp);
        }
        $this->db->insert_batch('JoinVote', $data);
    }
    
    /**
     * 添加一条投票记录
     * @param int $start_voteid
     * @param array $identify
     * @return int 投票记录编号
     */
    public function addVoteLog($start_voteid, $identify) {
        $identify['start_voteid'] = $start_voteid;
        $identify['vote_time'] = time();
        if (!empty($identify['openid'])) {
            $identify['openid'] = $identify['thirdtype'] . ':' . $identify['openid'];
        }
        $this->db->insert('VoteLog', $identify);
        return $this->db->insert_id();
    }
    
    public function getVoteResult($vid) {
        $choices = $this->getVoteChoices($vid);
        
        $query = $this->db->get_where('JoinVote', array('start_voteid'=>$vid));
        $votes = $query->result_array();
        $temp = array();
        foreach ($votes as $vote) {
            $cid = $vote['choiceid'];
            if(!array_key_exists($cid, $temp)) {
                $temp[$cid] = 0;
            }
            $temp[$cid]++;
        }
        $result = array();
        foreach ($choices as $choice) {
            $cid = $choice['choiceid'];
            $result[$cid] = array_key_exists($cid, $temp) ? $temp[$cid] : 0;
        }
        return $result;
    }
}
