<?php 
	$logid = $msg->logid;
	$time = time();
	$maxFriend = 30;
	do{
		$sql = "select * from ".$sql_table."map_fight_log where id=".$logid." and to_gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//没这条记妹	
		{
			$returnData->fail = 11;
			break;
		}
		
		if($result['to_gameid'] != $userData->gameid)//不能反击
		{
			$returnData->fail = 12;
			break;
		}
		
		if($result['type'])//已反击
		{
			$returnData->fail = 13;
			break;
		}

		require_once($filePath."map_fight_pk.php");
		if($returnData->fail)
			break;

		$sql = "update ".$sql_table."map_fight_log set type=1 where id=".$logid;
		$result = $conne->uidRst($sql);
		if(!$result)//更新日志失败	
		{
			$returnData->fail = 20;
			break;
		}
	}
	while(false);	
	

?> 