<?php 
	//ÇåºÃÓÑ
	if($msg->manager_key != 'hange0o0')
		return;
	require_once($filePath."tool/conn.php");
	$time = time() - 3600*24*3;
	$sql = "delete from ".$sql_table."friend_log where time<".$time;
	$conne->uidRst($sql);
	
?> 