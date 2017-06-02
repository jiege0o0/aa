<?php 
	//¾Ü¾ø¼ÓÈë¶ÓÎé
	$logid = $msg->logid;
	do{
		$sql = "update ".$sql_table."friend_log set time=0 where id=".$logid." and to_gameid='".$userData->gameid."'";
		$result = $conne->uidRst($sql);
		if(!$result)	
		{
			$returnData->fail = 1;
			break;
		}
		$returnData->data = 'ok';
	}
	while(false);	
?> 