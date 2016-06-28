<?php 
include('./lib.php');
include('./header.php');
if(($user = isLogin()) == false){
	header('location: index.php');
}
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
0 粉丝<br>
0 关注<br>
</div>
</div>
<div class="post">
<a class="username" href="profile.php?u=test">test</a> hello<br>
<i>11 分钟前 通过 web发布</i>
</div>

<?php include('./footer.php'); ?>
