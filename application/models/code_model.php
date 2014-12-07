<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Code_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getCodeInfo($code) {
        if(empty($code)) {
            return array();
        }
        $query = $this->db->get_where('Code', array('code'=>$code));
        return $query->row_array();
    }
}