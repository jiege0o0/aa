<?php 
$connect=mysql_connect($sql_url,$sql_user,$sql_password)or die('message=F,Could not connect: ' . mysql_error()); 
// mysql_query("CREATE DATABASE ".$sql_db,$connect)or die('Could not create database'); 
mysql_select_db($sql_db,$connect)or die('Could not select database'); 
mysql_query("set names utf8");

//ALTER TABLE `no1_user_data` ADD `pk_common` TEXT


/*mysql_query("
Create TABLE ".$sql_table."user_data(
gameid varchar(16) NOT NULL Unique Key,
uid INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
nick varchar(30),
head varchar(128),
word varchar(64),
exp INT UNSIGNED default 0,
rmb INT UNSIGNED default 0,
level TINYINT UNSIGNED default 1,
tec_force SMALLINT UNSIGNED default 0,
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
public_value Text,
land_key varchar(63),
last_land INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

mysql_query("
Create TABLE ".$sql_table."server_game(
id smallint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
gameid varchar(16),
game_data varchar(8138),
data_key varchar(16),
choose_num TINYINT UNSIGNED default 0,
last_time INT UNSIGNED,
INDEX(data_key)
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

for($i=1;$i<=10000;$i++)
{
	$arr = $server1[$i]; 
	mysql_query("
	insert into ".$sql_table."server_game(last_time,data_key) values(0,'".$i."')",
	$connect)or die("message=F,Invalid query: " . mysql_error()); 
}

mysql_query("
Create TABLE ".$sql_table."server_game_equal(
id smallint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
gameid varchar(16),
game_data varchar(8138),
data_key varchar(16),
choose_num TINYINT UNSIGNED default 0,
last_time INT UNSIGNED,
INDEX(data_key)
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

for($i=1;$i<=10000;$i++)
{
	$arr = $server1[$i]; 
	mysql_query("
	insert into ".$sql_table."server_game_equal(last_time,data_key) values(0,'".$i."')",
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





mysql_query("
Create TABLE ".$sql_table."main_pass(
id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
level smallint UNSIGNED,
data varchar(8138),
score mediumint UNSIGNED,
mkey varchar(32),
pk_version smallint UNSIGNED,
time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 


mysql_query("
Create TABLE ".$sql_table."map_fight_log(
id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
from_gameid varchar(16),
to_gameid varchar(16),
type TINYINT UNSIGNED,
content varchar(8138),
time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 



mysql_query("
Create TABLE ".$sql_table."map_fight(
id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
level smallint UNSIGNED,
gameid varchar(16),
content varchar(1023),
time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

for($j = 1;$j<=100;$j++)
{
	for($i=1;$i<=50;$i++)
	{
		mysql_query("
		insert into ".$sql_table."map_fight(level,time) values(".$j.",0)",
		$connect)or die("message=F,Invalid query: " . mysql_error()); 
	}
}*/

mysql_query("
Create TABLE ".$sql_table."skill_log(
id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
skillid TINYINT UNSIGNED,
gameid varchar(16),
content varchar(256),
time INT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 


mysql_query("
Create TABLE ".$sql_table."skill_total(
id TINYINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
num TINYINT UNSIGNED
)",$connect)or die("message=F,Invalid query: " . mysql_error()); 
for($j = 1;$j<=200;$j++)
{
	mysql_query("
	insert into ".$sql_table."skill_total(num) values(0)",
	$connect)or die("message=F,Invalid query: " . mysql_error()); 
}





//服务器状态信息表(openid,state_key)
//好友表（AID,BID,win1,win2,isBreak）
//好友PK表(from,to,info1,info2,type,time)
//好友对话表(from,to,type,content,time)
//赌场(from,to,info1,info2,type,step,time)
echo "成功".time();
?>