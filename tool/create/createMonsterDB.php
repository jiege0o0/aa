<?php 
$connect=mysql_connect($sql_url,$sql_user,$sql_password)or die('message=F,Could not connect: ' . mysql_error()); 
// mysql_query("CREATE DATABASE ".$sql_db,$connect)or die('Could not create database'); 
mysql_select_db($sql_db,$connect)or die('Could not select database'); 
mysql_query("set names utf8");

//ALTER TABLE `no1_user_data` ADD `pk_common` TEXT

if(!$_GET['table'])
	die('没有表格名传入');

$arr = explode(',',$_GET['table']);
foreach($arr as $key=>$sql_table)
{
	for($i = 10;$i<51;$i++)
	{
		mysql_query("
		Create TABLE ".$sql_table."_".$i."(
		id SMALLINT UNSIGNED,
		display INT UNSIGNED default 0,
		use_time INT UNSIGNED default 0,
		use_num INT UNSIGNED default 0,
		win INT UNSIGNED default 0
		)",$connect)or die("message=F,Invalid query: " . mysql_error()); 

		//往表插入数据
		for($j=1;$j<=100;$j++)
		{
			mysql_query("
			insert into ".$sql_table."_".$i."(id) values(".$j.")",
			$connect)or die("message=F,Invalid query: " . mysql_error()); 
		}
	}
	
	
}


echo "成功".time();
?>