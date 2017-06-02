<?php 
	$player_index = $msg->player_index;
	do{
		if($userData->getDiamond() < 100)//钻石不足
		{	
			$returnData->fail = 3;
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		
		$time = time();
		$month =  (int)(date('m', $time));
		$day =  (int)(date('d', $time));
		$todayTable = $sql_table."team_pve_".$month.'_'.$day;
		$sql = "select * from ".$todayTable." where id=".$userData->active->team_pve->team;
		$sqlResult = $conne->getRowsRst($sql);
		
		$playerData = json_decode($sqlResult['player'.$player_index]);
		if($playerData->gameid != $userData->gameid)//用户对不上
		{	
			$returnData -> fail = 1;
			break;
		}
		$playerData->buy_time ++;
		
		$sql = "update ".$todayTable." set player".$player_index."='".json_encode($playerData)."' where id=".$userData->active->team_pve->team;
		$conne->uidRst($sql);
		
		
		$userData->addDiamond(-100);
		$userData->write2DB();		
	}while(false);
?> 