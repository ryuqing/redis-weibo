#这是一个Redis仿微博热数据的写入小实验

设计user表
注册操作，即执行者3个操作

set user:userid:1:username admin
set user:userid:1:password admin

set user:username:admin:userid 1

发微博：
1：把微博内容相关放在哈希结构表里
2：把自己发的微博放在一个有序集合里,只要前20个。用于粉丝取出
3：把自己发的微博id放在一个链表里，用于自己看自己微博用的。1000个之前的旧微博pop出放在一个全局链表里，写个定时任务将这些冷数据写入mysql里

