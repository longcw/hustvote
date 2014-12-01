<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	/**
	 * 邮箱是否已经注册
	 * @param string $email
	 * @return boolean
	 */
	public function isEmailExist($email) {
		$query = $this->db->get_where('User', array('email'=>$email), 1);
		return ($query->num_rows() >= 1);
	}
	
	/**
	 * 用户注册
	 * @param array $data
	 *  注册信息，包括 password email nickname
	 * @param string|int $callback
	 * @return boolean
	 */
	public function doReg($data, &$callback) {
		if(count($data) != 3) {
			$callback = 'ArrayCountWrong';
			return false;
		}
		if($this->isEmailExist($data['email'])){
			$callback = 'EmailExisted';
			return false;
		}
		
		$data['groupid'] = 1;	//普通用户
		$data['createtime'] = time();
		$this->db->insert('User', $data);
		$callback = $this->db->insert_id();
		return true;
	}
	
	/**
	 * 用户登录
	 * @param array $data
	 *  email password
	 * @param string|int $callback
	 * @return boolean
	 */
	public function doLogin($data, &$callback) {
		$query = $this->db->get_where('User', array('email'=>$data['email']), 1);
		if($query->num_rows < 1) {
			$callback = 'EmailNotExist';
			return false;
		}
		$row = $query->row_array();
		if($data['password'] === $row['password']) {
			$callback = $row['uid'];
			return true;
		} else {
			$callback = 'PasswordWrong';
			return false;
		}
	}
	
	/**
	 * 查询用户信息
	 * @param int $uid
	 * @return array $result
	 * 	$result包括uid email nickname groupname groupid createtime
	 */
	public function getUserInfo($uid) {
		$this->db->join('Group', 'Group.groupid=User.groupid');
		$this->db->select('Group.groupname')->select('User.*');
		$query = $this->db->get_where('User', array('uid'=>$uid), 1);
		$result = $query->row_array();
		if(empty($result)) {
			return array();
		} else {
			unset($result['password']);
			return $result;
		}
	}
}