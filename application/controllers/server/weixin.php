<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Weixin extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $options = array(
            'token' => 'wxa4f573631ba6c5b2', //填写你设定的key
            'encodingaeskey' => 'encodingaeskey', //填写加密用的EncodingAESKey
            'appid' => 'wxa4f573631ba6c5b2', //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret' => 'xxxxxxxxxxxxxxxxxxx', //填写高级调用功能的密钥
        );

        $this->load->library('wechat', $options);
        $this->load->model('vote_model');
    }

    public function index() {
        //$this->wechat->valid();
        $type = $this->wechat->getRev()->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $text = $this->wechat->getRevContent();
                $this->_onTextMessage($text);
                break;
            case Wechat::MSGTYPE_EVENT:
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $this->_doHelpMessage();
        }
    }

    private function _doHelpMessage() {
        $this->wechat->text('请输入投票编号或邀请码')->reply();
    }

    private function _onTextMessage($text) {
        $msg = trim($text);
        if ($this->_doVidMessage($msg) || $this->_doCodeMessage($msg)) {
            return true;
        }
        $this->_doHelpMessage();
        return false;
    }

    private function _doVidMessage($vid, $code = null) {
        $vote = $this->vote_model->getVoteTitle($vid);
        if (empty($vote)) {
            return false;
        }

        $data = array(
            '0' => array(
                'Title' => $vote['title'],
                'Description' => $vote['summary'],
                'PicUrl' => $vote['image'],
                'Url' => base_url('vote/join/' . $vid . ($code ? "?code=$code" : ""))
            )
        );
        $this->wechat->news($data)->reply();
        return true;
    }

    private function _doCodeMessage($code) {
        $codelog = $this->vote_model->getVoteIdByCode($code);
        if (empty($codelog)) {
            return false;
        }

        if ($codelog['is_voted']) {
            $data = '该邀请码已经在 ' . date('Y年n月j日 G点i分', $codelog['vote_time']) . ' 参与投票';
            $this->wechat->text($data)->reply();
            return true;
        }
        return $this->_doVidMessage($codelog['start_voteid'], $code);
    }

}
