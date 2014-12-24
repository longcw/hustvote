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
    
    public function getClassLog($building = null) {
        $this->db->where('building', $building);
        $this->db->limit(1)->order_by('update_time desc');
        $query = $this->db->get('ClassLog');
        return $query->row_array();
    }
    
    public function setClassLog($update_time, $building = null) {
        $this->db->insert('ClassLog', array('update_time'=>$update_time, 'building' => $building));
        return $this->db->insert_id();
    }
    
    public function setClassData($data, $logid) {
        $this->delClassData();
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
    
    public function setClassData_JWC($building, $data, $logid) {
        $this->delClassData($building);
        $idata = array();
        foreach ($data as $value) {
            $item = array(
                'classlogid' => $logid,
                'building' => $building,
                'classdata' => $value
            );
            array_push($idata, $item);
        }
        //var_dump($idata);
        $this->db->insert_batch('ClassData_JWC', $idata);
    }
    
    public function getClassData_JWC($logid) {
        $this->db->where('classlogid', $logid)->order_by('classdataid asc');
        $query = $this->db->get('ClassData_JWC');
        $result = $query->result_array();
        return $result;
    }
    
    public function delClassData($building = null) {
        if(!empty($building)) {
            $this->db->where('building', $building);
            $this->db->delete('ClassData_JWC');
        } else {
            $this->db->delete('ClassData');
        }
        
    }
}