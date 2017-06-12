<?php 
$connect=mysql_connect($sql_url,$sql_user,$sql_password)or die('message=F,Could not connect: ' . mysql_error()); 
// mysql_query("CREATE DATABASE ".$sql_db,$connect)or die('Could not create database'); 
mysql_select_db($sql_db,$connect)or die('Could not select database'); 
mysql_query("set names utf8");

//ALTER TABLE `no1_user_data` ADD `pk_common` TEXT




//不要服务器ID，所有服共同评论
for($i = 1;$i<=100;$i++)
{
	mysql_query("
	Create TABLE monster_talk_".$i."(
	id SMALLINT UNSIGNED,
	talk_key varchar(16),
	talk varchar(8138),
	good INT UNSIGNED default 0,
	bad INT UNSIGNED default 0,
	time INT UNSIGNED default 0,
	INDEX(time)
	)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

	//往表插入数据
	for($j=1;$j<=50;$j++)
	{
		mysql_query("
		insert into monster_talk_".$i."(id) values(".$j.")",
		$connect)or die("message=F,Invalid query: " . mysql_error()); 
	}
}

mysql_query("
Create TABLE monster_star(
id SMALLINT UNSIGNED,
s1 INT UNSIGNED default 0,
s2 INT UNSIGNED default 0,
s3 INT UNSIGNED default 0,
s4 INT UNSIGNED default 0,
s5 INT UNSIGNED default 0
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 
for($j=1;$j<=100;$j++)
{
	mysql_query("
	insert into monster_star(id) values(".$j.")",
	$connect)or die("message=F,Invalid query: " . mysql_error()); 
}




//服务器状态信息表(openid,state_key)
//好友表（AID,BID,win1,win2,isBreak）
//好友PK表(from,to,info1,info2,type,time)
//好友对话表(from,to,type,content,time)
//赌场(from,to,info1,info2,type,step,time)
echo "成功".time();
?>