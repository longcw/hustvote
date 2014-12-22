<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classroom extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('class_model');
        date_default_timezone_set('PRC');
    }
    
    public function index() {
        $data['classlog'] = $this->class_model->getClassLog();
        if(empty($data['classlog'])) {
            $this->update();
            return;
        }
        $data['classdata'] = $this->class_model->getClassData($data['classlog']['classlogid']);
        
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '东九教室';
        $header ['act'] = 'hall';
        
        
        $this->load->view('header', $header);
        $this->load->view('class', $data);
        $this->load->view('footer');
    }
    
    private function _getData() {
        $pattern = '/<li>([A-D]\d{3})<\/li>/';
        $base = 'http://wap.hustonline.net/classroom/info?cr-building-select=9&cr-class-check%5B%5D=class';
        $classroom = array();
        for($i = 1; $i <= 5; $i++) {
            $str = file_get_contents($base . $i);
            $matches = array();
            preg_match_all($pattern, $str, $matches);
            
            foreach ($matches[1] as $key) {
                if(!array_key_exists($key, $classroom)) {
                    $classroom[$key] = 0;
                }
                $j = 0x10;
                $classroom[$key] |= ($j >> (5-$i));
            }
        }
        //var_dump(array_map('decbin', $classroom));
        return $classroom;
    }
    
    public function update() {
        $classdata = $this->_getData();
        if(!empty($classdata)) {
            $logid = $this->class_model->setClassLog(time());
            $this->class_model->setClassData($classdata, $logid);
        }
        redirect('classroom/index');
    }

}
