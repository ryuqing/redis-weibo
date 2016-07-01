<?php
/*
*功能说明：关注某个用户所进入的主页
*每人都有自己的粉丝记录 set一次
*每人有自己的关注记录 set一次
*aid 关注 bid：
*following ==> aid (bid)  //关注者
*follower  ==> bid(aid)   //被关注者

步骤：
1：获取被关注者的用户名、id
2:
3:查询此id，是否在我的follower集合里
*/

include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false) {
	header('location: index.php');
	exit;
}

$r = conredis();
$u = G('u');
$prouid = $r->get('user:username:'.$u.':userid');
if(!$prouid) {
	error('非法用户');
	exit;
}

$isf = $r->sismember('following:'.$user['userid'], $prouid);
$isfstatus = $isf?'0':'1';  //f是1说明要关注，f是0说明取消关注
$isfoltext = $isf?'取消判断':'关注TA';



?>
<h2 class="username">test</h2>
<a href="follow.php?uid=<?php echo $prouid; ?>&f=<?php echo $isfstatus; ?>" class="button"><?php echo $isfoltext; ?></a>

<div class="post">
<a class="username" href="profile.php?u=test">test</a> 
world<br>
<i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>

<?php include('./footer.php'); ?>