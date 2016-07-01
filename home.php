<?php 
include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false){
	header('location: index.php');
	exit;
}

//去除自己发的和粉主推送过来的信息
$r = new conredis();
//去除自己发的和粉主推过的信息
$r->ltrim('receivepost:'.$user['userid'], 0, 49);
$r->sort('receivepost:'.$user['userid'], array('sort'=>'desc', 'get'=>'post:postid:*:content'));

//计算几个粉丝和关注，就是计算集合的元素个数
$myfans = $r->sCrad('follower:'.$user['userid']);
$mystar = $r->sCrad('following:'.$user['userid']);


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
<?php foreach ($newpost as $post) {
		# code...
?>
<div class="post">
<a class="username" href="profile.php?u=test">test</a> <?php echo $post; ?><br>
<i>11 分钟前 通过 web发布</i>
</div>
<?php } ?>
<?php include('./footer.php'); ?>
