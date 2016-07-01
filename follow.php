<?php 
/*
* 功能说明：完成关注
*/
include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false) {
	header('location: index.php');
	exit;
}

$uid = G('uid');
$f   = G('f');

/*
* 判断uid,是否合法
* uid 是否是自己
* 此处略
*/
$r = conredis();
$uname = $r->get('user:userid:'.$uid.':username');  //此值暂时有问题，视频观看到53分钟

if($f == 1) {
	$r->sadd('following:', $user['userid'], $uid);
	$r->sadd('follower:',  $uid, $user['userid']);	
} else {
	$r->srem('following:', $user['userid'], $uid);
	$r->srem('follower:',  $uid, $user['userid']);	
}

header('location: profile.php?u='.$uname);

?>





<?php include('./footer.php'); ?>