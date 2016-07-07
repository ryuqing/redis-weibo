<?php 
include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false){
	header('location: index.php');
	exit;
}

//去除自己发的和粉主推送过来的信息
$r = conredis();
//去除自己发的和粉主推过的信息
$r->ltrim('receivepost:'.$user['userid'], 0, 49);
//$newpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc', 'get'=>'post:postid:*:content'));

$newpost = $r->sort('receivepost:'.$user['userid'], array('sort'=>'desc'));

//计算几个粉丝和关注，就是计算集合的元素个数
$myfans = $r->sCard('follower:'.$user['userid']);
$mystar = $r->sCard('following:'.$user['userid']);
print_r($newpost);

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
