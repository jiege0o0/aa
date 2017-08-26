<?php 
//创建一个月的副本表格
$connect=mysql_connect($sql_url,$sql_user,$sql_password)or die('message=F,Could not connect: ' . mysql_error()); 
// mysql_query("CREATE DATABASE ".$sql_db,$connect)or die('Could not create database'); 
mysql_select_db($sql_db,$connect)or die('Could not select database'); 
mysql_query("set names utf8");

//ALTER TABLE `no1_user_data` ADD `pk_common` TEXT

$month = $_GET["month"];

require_once($filePath."random_fight_card.php");
require_once($filePath."tool/tool.php");

//不要服务器ID，所有服共同评论
for($i = 1;$i<=31;$i++)
{
	mysql_query("
	Create TABLE ".$sql_table."team_pve_".$month."_".$i."(
	id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nick varchar(30),
	player1 varchar(2048),
	player2 varchar(2048),
	player3 varchar(2048),
	game_data varchar(4096),
	time INT UNSIGNED default 0
	)",$connect)or die("message=F,Invalid query: " . mysql_error()); 
	
	//生成关卡数据(6个难度)
	for($j = 1;$j<=6;$j++)
	{
		$name = 'pve'.$month."_".$i."_".$j;
		createMonsterData($name,25,$j * 5);
	}
}

echo "成功".time();




//生成关卡数据
function createMonsterData($name,$num,$level){
	global $serverID,$dataFilePath;
	$file  = $dataFilePath.'dungeon_game/'.$name.'.txt';
	if(is_file($file))
		return;
	$result = array();
	for($i=0;$i<$num;$i++)
	{
		$oo = randomFightCard($level);
		array_push($result,$oo);
	}
	usort($result,sortCost);
	
	for($i=0;$i<$num;$i++)
	{
		$list = $result[$i]['list'];
		$result[$i] = new stdClass();
		$result[$i]->list = $list;
	}
	
	$content = new stdClass();
	$content->levels = $result;
	$content = json_encode($content);
	file_put_contents($file,$content,LOCK_EX);
}

	function sortCost($a,$b){
		if($a['cost'] < $b['cost'])
			return -1;
		if($a['cost'] > $b['cost'])
			return 1;
	}



?>