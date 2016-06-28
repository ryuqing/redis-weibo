<?php 
/*
*功能说明：发布微博
*1、判断是否登录
*2、接收post内容
*3、set redis
*/

include('lib.php');
include('header.php');

if(($user = isLogin()) == false) {
	header('location: home.php');
	exit;
}

$r = conredis();
$postid = $r->incr('global:postid');
$r->set('post:postid:'.$postid.':userid',$user['userid']);
$r->set('post:postid:'.$postid.':time',time());
$r->set('post:postid:'.$postid.':content',$content)

include('./footer.php');
?>