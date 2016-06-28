<?php 
/***
功能说明：基础函数库，做简单封装
***/

function P($key) {
	return $_POST[$key];
}

function G($key) {
	return $_GET[$KEY];
}

//error

function error($msg) {
	echo '<div>';;
	echo $msg;
	echo '</div>';
	include('./footer.php');
	exit;
}

function conredis() {
	static $r = null;
	if($r !== null){
		return $r;	
	}

	$r = new redis();
	$r->connect('127.0.0.1', 6379);	
	return $r;
}

?>