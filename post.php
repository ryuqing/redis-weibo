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

//把微博放在哈希结构里
$r->hmset('post:postid:'.$postid, array('userid' => $user['userid'], 'time' => time(), 'content' => $content, 'username', $user['username']));

//把自己发的微博放在一个有序集合里,只要前20个
$r->zadd('newpost:userid:'.$user['userid'],$postid, $postid);
if($r->zcard('newpost:userid'.$user['userid'] > 20)) {
	$r->zremrangebyrank('newpost:userid'.$user['userid'], 0, 0);
}

/*
推送微博模型
//要把微博推送给自己的粉丝
$fans = $r->smembers('follower:'.$user['userid']);
$fans[] = $user['userid'];

foreach ($fans as $fansid) {
	$r->lpush('receivepost:'.$fansid,$postid);
}
*/
echo '发布成功';

include('./footer.php');
?>