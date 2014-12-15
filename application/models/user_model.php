<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
        $query = $this->db->get_where('User', array('email' => $email), 1);
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
        if (count($data) != 3) {
            $callback = 'ArrayCountWrong';
            return false;
        }
        if ($this->isEmailExist($data['email'])) {
            $callback = 'EmailExisted';
            return false;
        }

        $data['groupid'] = 1; //普通用户
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
        if(empty($data)) {
            $callback = 'ArrayCountWrong';
            return false;
        }
        $query = $this->db->get_where('User', array('email' => $data['email']), 1);
        if ($query->num_rows < 1) {
            $callback = 'EmailNotExist';
            return false;
        }
        $row = $query->row_array();
        if ($data['password'] === $row['password']) {
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
        $query = $this->db->get_where('User', array('uid' => $uid), 1);
        $result = $query->row_array();
        if (empty($result)) {
            return array();
        } else {
            unset($result['password']);
            return $result;
        }
    }

    /**
     * 设置验证信息
     * @param int $uid
     * @return array
     */
    public function setVerifyToken($uid) {
        if (!is_numeric($uid)) {
            return null;
        }
        $data['verify_token'] = md5(uniqid(rand(), true) . $uid);
        $data['exp_time'] = time() + 24 * 60 * 60;
        $this->db->update('User', $data, array('uid' => $uid));
        return $data;
    }

    /**
     * 验证邮箱
     * @param int $uid
     * @param string $token
     * @return boolean
     */
    public function verifyToken($uid, $token) {
        $where = array(
            'uid' => $uid,
            'verify_token' => $token,
            'exp_time >' => time(),
        );
        $this->db->select('uid')->limit(1);
        $query = $this->db->get_where('User', $where);
        $row = $query->row_array();
        if (!empty($row)) {
            $this->db->update('User', array('exp_time' => 0, 'is_verified' => 1), array('uid' => $row['uid']));
            return true;
        } else {
            return false;
        }
    }

    public function setLogin($uid) {
        $this->session->set_userdata('uid', $uid);
    }

    public function setLogout() {
        $this->session->unset_userdata('uid');
    }

    /**
     * 发送验证邮件
     * @param array $userinfo 用户信息
     * @return boolean
     */
    public function sendVerifyEmail($userinfo) {
        if (empty($userinfo)) {
            return false;
        }
        $token = $this->setVerifyToken($userinfo['uid']);
        if (empty($token)) {
            return false;
        }
        $to[$userinfo['nickname']] = $userinfo['email'];
        $subject = 'HustVote用户邮箱验证';
        $body = "亲爱的 $userinfo[nickname]:\n" .
                " 请点击下面的链接验证您的邮箱：\n" . base_url("user/verify/$userinfo[uid]/" . $token['verify_token']) .
                "\n链接有效期为24小时，请于 " . date('Y年m月d H时i分', $token['exp_time']) . ' 前验证。';
        $body = nl2br($body);
        $this->load->library('mailer');
        $state = $this->mailer->sendmail($to, $subject, $body);
        //var_dump($state);
        return $state;
    }

}
