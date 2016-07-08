<?php 
/*
*冷数据写入mysql  PS：写完还未测试
*/
include('./lib.php');
$r = connredis();

$sql = 'insert into post(postid, userid, username, time, content) values ';
$i = 0;
while ($r->llen('global:store') && $i++ < 1000) {
	$postid = $r->rpop('global:store');
	$post = $r->get('post:postid:'.$postid, array('userid', 'username', 'time', 'content'));
	$sql .= "($postid,". $post['userid'].",'".$post['username']."',". $post['time']."',".$post['content'])."'),";
	if($i == 0 ) {
		echo 'no job';
		eixt;
	}
}
$sql = substr($sql, 0, -1);

//连接接数据库，写入。。。。

?>