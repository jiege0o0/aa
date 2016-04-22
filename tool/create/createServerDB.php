<?php 
$connect=mysql_connect($sql_url,$sql_user,$sql_password)or die('message=F,Could not connect: ' . mysql_error()); 
// mysql_query("CREATE DATABASE ".$sql_db,$connect)or die('Could not create database'); 
mysql_select_db($sql_db,$connect)or die('Could not select database'); 
mysql_query("set names utf8");

//ALTER TABLE `no1_user_data` ADD `pk_common` TEXT


mysql_query("
Create TABLE ".$sql_table."user_data(
gameid varchar(16) NOT NULL Unique Key,
uid INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
nick varchar(30),
head varchar(128),
exp INT UNSIGNED default 0,
level TINYINT UNSIGNED default 1,
tec_force SMALLINT UNSIGNED default 1,
award_force SMALLINT UNSIGNED default 0,
coin BIGINT UNSIGNED default 0,
prop varchar(4096),
energy varchar(255),
diamond varchar(255),
friends varchar(255),
collect Text,
tec Text,
server_game Text,
server_game_equal Text,
main_game Text,
day_game Text,
honor Text,
active Text,
pk_common Text,
land_key varchar(63),
last_land INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 


mysql_query("
Create TABLE ".$sql_table."server_game_1(
id TINYINT UNSIGNED,
gameid varchar(16),
game_data varchar(8138),
result TINYINT UNSIGNED,
choose_num TINYINT UNSIGNED default 0,
last_time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

// 往表插入数据
require_once($filePath."tool/create/server_data.php");
for($i=1;$i<=100;$i++)
{
	$arr = $server1[$i]; 
	mysql_query("
	insert into ".$sql_table."server_game_1(id,gameid,game_data,result,last_time) values(".$arr['id'].
	",'".$arr['gameid']."'".
	",'".$arr['game_data']."'".
	",".$arr['result'].
	",".$arr['last_time'].")",
	$connect)or die("message=F,Invalid query: " . mysql_error()); 
}

mysql_query("
Create TABLE ".$sql_table."server_game_equal_1(
id TINYINT UNSIGNED,
gameid varchar(16),
game_data varchar(8138),
result TINYINT UNSIGNED,
choose_num TINYINT UNSIGNED default 0,
last_time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

// 往表插入数据
for($i=1;$i<=100;$i++)
{
	$arr = $server2[$i]; 
	mysql_query("
	insert into ".$sql_table."server_game_equal_1(id,gameid,game_data,result,last_time) values(".$arr['id'].
	",'".$arr['gameid']."'".
	",'".$arr['game_data']."'".
	",".$arr['result'].
	",".$arr['last_time'].")",
	$connect)or die("message=F,Invalid query: " . mysql_error()); 
}

//好友相关
mysql_query("
Create TABLE ".$sql_table."user_friend(
gameid varchar(16) PRIMARY KEY,
friends varchar(8138),
friends_info Text
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

mysql_query("
Create TABLE ".$sql_table."friend_together(
friend_key varchar(32),
win1 INT UNSIGNED default 0,
win2 INT UNSIGNED default 0,
friend1_cards  varchar(512),
friend2_cards  varchar(512),
friend1_info  varchar(1024),
friend2_info varchar(1024),
last_winner varchar(16),
last_time INT UNSIGNED default 0
)",$connect)or die("message=F,Invalid query: " . mysql_error());


mysql_query("
Create TABLE ".$sql_table."friend_log(
id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
from_gameid varchar(16),
to_gameid varchar(16),
type TINYINT UNSIGNED,
content varchar(8138),
time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 



//服务器状态信息表(openid,state_key)
//好友表（AID,BID,win1,win2,isBreak）
//好友PK表(from,to,info1,info2,type,time)
//好友对话表(from,to,type,content,time)
//赌场(from,to,info1,info2,type,step,time)
echo "成功".time();
?>