<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ErrorHandler {
	private $errorMap = array(
		'EmailExisted' => '邮箱已经被注册',
		'ArrayCountWrong' => 'array count wrong',
		'EmailNotExist' => '邮箱不存在',
		'PasswordWrong' => '密码错误',
                'TokenError'    =>  'token error',
                'NoRight'   => '权限不足',
                'ErrorPage' => '错误的页码',
                'ErrorVote' => '投票错误'
	);
	
	/**
	 * 返回错误信息
	 * @param string $errorcode
	 * @return string
	 */
	public function getErrorDes($errorcode) {
		if(isset($this->errorMap[$errorcode])) {
			return $errorcode . ':' . $this->errorMap[$errorcode];
		} else {
			return 'Unknown';
		}
	}
}