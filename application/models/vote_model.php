<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function addNewVote($data, &$callback) {
        $choices = $data['choice'];
        $details = $data['detail'];
        unset($data['choice']);
        unset($data['detail']);
        
        //插入startvote
        $this->db->insert('StartVote', $data);
        $id = $this->db->insert_id();
        $code = str_pad($id, 8, rand(10000000, 99999999), STR_PAD_BOTH);
        $this->db->where('start_voteid', $id);
        $this->db->update('StartVote', array('code'=>$code));
        
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
        $callback = $id;
        return true;
    }
}
