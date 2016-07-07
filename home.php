<?php 
include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false){
	header('location: index.php');
	exit;
}


$r = conredis();
/* 推送模型中
//取出自己发的和粉主推过的信息
$r->ltrim('receivepost:'.$user['userid'], 0, 49);
//$newpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc', 'get'=>'post:postid:*:content'));
*/

/*自己取微博*/
$star = $r->smembers('following:'.$user['userid']);
$star[] = $user['userid'];

$lastpull = $r->get('lastpull:userid:'.$user['userid']);
if(!$lastpull) {
	$lastpull = 0;
}

//拉取最新数据
foreach ($star as $s) {
	$latest = array_merge($latest, $r->zrangebyscore('newpost:userid:'.$s, $lastpull+1, 1<<32-1));
}

//更新lastpull
if(!empty($latest)) {
	$r->set('lastpull:userid:'.$user['userid'], end($latest));
}

//循环把latest放到自己主页应该收取的微博链里
foreach ($latest as $l) {
	$r->lpush('receivepost:'.$user['userid'], $l);
}

//保存个人主页，至多有1000条微博
$r->ltrim('receivepost:'.$user['userid'], 0, 999);

$newpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc'));

//计算几个粉丝和关注，就是计算集合的元素个数
$myfans = $r->sCard('follower:'.$user['userid']);
$mystar = $r->sCard('following:'.$user['userid']);

?>

<div id="postform">
<form method="POST" action="post.php">
<?php echo $user['username']; ?>, 请发牢骚?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="status"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $myfans; ?> 粉丝<br>
<?php echo $mystar; ?> 关注<br>
</div>
</div>
<?php foreach ($newpost as $postid) { 
	$post = $r->hMGet('post:postid:'.$postid, array('userid', 'time', 'content', 'username'));
?>
<div class="post">
<a class="username" href="profile.php?u=test"><?php echo $post['username']; ?></a> <?php echo $post['content']; ?><br>
<i> <?php echo $post['time']; ?>分钟前 通过 web发布</i>
</div>
<?php } ?>
<?php include('./footer.php'); ?>
