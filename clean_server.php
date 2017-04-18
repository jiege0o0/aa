<?php 
	$serverID = $_GET["serverid"];
	$filePath = dirname(__FILE__).'/';
	require_once($filePath."_config.php");
	require_once($filePath."tool/conn.php");
	$time = time() - 3600*24*3;//3天前
	
	$sql = "DELETE FROM ".$sql_table."friend_log where time<".$time;
	$result = $conne->uidRst($sql);
	echo $result.'-'.time();
	
	
		
?> 