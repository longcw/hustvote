<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getClassData($logid) {
        $this->db->where('classlogid', $logid)->order_by('classid asc');
        $query = $this->db->get('ClassData');
        return $query->result_array();
    }
    
    public function getClassLog() {
        $this->db->limit(1)->order_by('update_time desc');
        $query = $this->db->get('ClassLog');
        return $query->row_array();
    }
    
    public function setClassLog($update_time) {
        $this->db->insert('ClassLog', array('update_time'=>$update_time));
        return $this->db->insert_id();
    }
    
    public function setClassData($data, $logid) {
        $idata = array();
        foreach ($data as $key => $value) {
            $item = array(
                'classlogid' => $logid,
                'classid' => $key,
                'classdata' => $value
            );
            array_push($idata, $item);
        }
        //var_dump($idata);
        $this->db->insert_batch('ClassData', $idata);
    }
}