<?php 

include('lib.php');
include('header.php');

/*
功能说明：登录功能
*1：接收$_POST数据判断数据完整性
*2：查询用户名是否存在
*3：设置cookie
*/

$username = P('username');
$password = P('password');
$authid = randsecret();

if(!$username || !$password) {
	error('请输入完整信息');
}

$r = conredis();
$userid = $r->get('user:username:'.$username.':userid');

if(!$userid){
	error('用户名不存在');
}

$p = $r->get('user:userid:'.$userid.':password');
if($password != $p) {
	error('密码不对');
}

//登录成功设置cookie
setcookie('username', $username);
setcookie('userid', $userid);
//验证放cookie 又放服务器
$r->set('user:userid:'.$userid.':authid', $authid);
setcookie('authid', $authid); 

header('location:home.php');

?>