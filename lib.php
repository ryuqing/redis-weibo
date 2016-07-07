<?php 
/***
*
*功能说明：基础函数库，做简单封装
*
***/

function P($key) {
	return $_POST[$key];
}

function G($key) {
	return $_GET[$key];
}

//error

function error($msg) {
	echo '<div>';;
	echo $msg;
	echo '</div>';
	include('./footer.php');
	exit;
}

//connect redis
function conredis() {
	static $r = null;
	if($r !== null){
		return $r;	
	}

	$r = new redis();
	$r->connect('127.0.0.1', 6379);	
	return $r;
}

//判断用户是否登录
function isLogin() {
	if(!$_COOKIE['userid'] || !$_COOKIE['username']) {
		return false;
	}
	if(!$_COOKIE['authid']){
		return false;
	}

	$r = conredis();
	$authid = $r->get('user:userid:'.$_COOKIE['userid'].':authid');
	if($authid != $_COOKIE['authid']) {
		return false;
	}

	return array('userid' => $_COOKIE['userid'], 'username' => $_COOKIE['username']);
}

function randsecret() {
	$str = 'qerjkfjdshaoiewqpxngeqad!@#$%^&*fa@$2432sffsaoewqiurqerqr1234567098@';
	return substr(str_shuffle($str), 0, 16);
}


?>