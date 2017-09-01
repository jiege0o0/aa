<?php 
	$logid = $msg->logid;
	$isFromBack = true;
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
		
		$sql = "select pk_common,public_value,nick from ".$sql_table."user_data where gameid='".$result['from_gameid']."'";
		$otherResult = $conne->getRowsRst($sql);
		if(!$otherResult)
		{
			$returnData -> fail = 4;
			break;
		}
		$pkComment = json_decode($otherResult['pk_common']);
		if(!$pkComment->map->last_pk_data)
		{
			$returnData -> fail = 3;
			break;
		}
		$team2Data = $pkComment->map->last_pk_data;
		$level = $pkComment->map->level;
		$fightEnemy = new stdClass();
		$fightEnemy->nick = base64_encode($otherResult['nick']);
		$fightEnemy->gameid = $result['from_gameid'];

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