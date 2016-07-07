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
	header('location: index.php');
	exit;
}
$content = P('status');
if(!$content) {
	error('请填写内容');
}

$r = conredis();
$postid = $r->incr('global:postid');

/* 之前设计的表
$r->set('post:postid:'.$postid.':userid',$user['userid']);
$r->set('post:postid:'.$postid.':time',time());
$r->set('post:postid:'.$postid.':content',$content);
*/

$r->hmset('post:postid:'.$postid, array('userid' => $user['userid'], 'time' => time(), 'content' => $content, 'username', $user['username']));
echo '发布成功';

//要把微博推送给自己的粉丝
$fans = $r->smembers('follower:'.$user['userid']);
$fans[] = $user['userid'];

foreach ($fans as $fansid) {
	$r->lpush('receivepost:'.$fansid,$postid);
}
include('./footer.php');
?>