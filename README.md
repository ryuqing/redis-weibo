#这是一个Redis仿微博热数据的写入小实验

设计user表
注册操作，即执行者3个操作

set user:userid:1:username admin
set user:userid:1:password admin

set user:username:admin:userid 1

发微博：
set post:postid:1:time timestamp
set post:postid:1:userid 1
set post:postid:1:postcontent '前年买了个表''
