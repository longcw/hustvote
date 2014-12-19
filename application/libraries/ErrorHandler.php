<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ErrorHandler {

    private $errorMap = array(
        'EmailExisted' => '邮箱已经被注册',
        'ArrayCountWrong' => 'array count wrong',
        'EmailNotExist' => '邮箱不存在',
        'PasswordWrong' => '密码错误',
        'TokenError' => 'token error',
        'NoRight' => '权限不足',
        'ErrorPage' => '错误的页码',
        'ErrorVote' => '投票错误'
    );
    private $codeMap = array(
        1000 => 'ok',
        1001 => '用户名或密码错误',
        1002 => '未登录',
        1003 => '没有更多内容了',
        1004 => '无效的投票ID',
        
        4000 => 'init',
    );

    /**
     * 返回错误信息
     * @param string $errorcode
     * @return string
     */
    public function getErrorDes($errorcode) {
        if (isset($this->errorMap[$errorcode])) {
            return $errorcode . ':' . $this->errorMap[$errorcode];
        } else {
            return 'Unknown';
        }
    }

    public function getCodeMsg($code) {
        if (isset($this->codeMap[$code])) {
            return $this->codeMap[$code];
        } else {
            return 'unknown';
        }
    }

}
