<?php 
/***

功能说明：注册用户页
逻辑步骤：
1：接收$_POST参数，判断用户名、密码是否完整
2：连接redis，查询该用户名，判断是否存在
3：写入redis
4：登陆操作

***/
include('lib.php');
include('header.php');

if(isLogin() !== false) {
	header('location: home.php');
	exit;
}

$username = P('username');
$password = P('password');
$rpassword = P('rpassword');

if(!$username || !$password || !$rpassword) {
	error('信息输入不完整');
}

//判断密码是否输入一致
if($password !== $rpassword){
	error('2次密码输入不一致');
}

//连接redis
$r = conredis();

//查询用户名是否已经被注册
$checkUser = $r->get('user:username:'.$username);
if($checkUser){
	error('用户名已经被注册');
}

//获取userid
$userid = $r->incr('global:userid');

$r->set('user:userid:'.$userid.':usrname',$username);
$r->set('user:userid:'.$userid.':password',$password);

$r->set('user:username:'.$username.':userid',$userid);

setcookie('username', $username);
setcookie('userid', $userid);
header('location: home.php');

include('footer.php');


?>