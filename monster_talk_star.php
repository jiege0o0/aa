<?php 
	require_once($filePath."tool/conn.php");
	$star = $msg->star;
	$monster = $msg->monster;
	do{
	
		$sql = $sql = "update monster_star set s".$star."=s".$star."+1 where id = ".$monster;
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 2;
			debug($sql);
			break;
		}
		$returnData->data = 'ok';
	}
	while(false);
	
	
	
		
?> 