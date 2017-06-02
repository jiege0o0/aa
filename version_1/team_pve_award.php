<?php 
	require_once($filePath."get_monster_collect.php");
	$isYesterday = $msg->yesterday;
	$award_index = $msg->award_index;
	$player_index = $msg->player_index;
	$userData->resetTeamGame();
	do{
		
		$time = time();
		$team = $userData->active->team_pve->team;
		if($isYesterday)
		{
			$team = $userData->active->team_pve->yteam;
			if(!$team || (int)(date('H', $time)) >= 12)//没奖领或今天过了12点
			{
				$returnData -> fail = 4;
				break;
			}
			$time -= 3600*24;
		}
		$month =  (int)(date('m', $time));
		$day =  (int)(date('d', $time));
		
		$todayTable = $sql_table."team_pve_".$month.'_'.$day;
		$sql = "select * from ".$todayTable." where id=".$team;
		$sqlResult = $conne->getRowsRst($sql);
		
		$playerData = json_decode($sqlResult['player'.$player_index]);
		if($playerData->gameid != $userData->gameid)//用户对不上
		{	
			$returnData -> fail = 1;
			break;
		}
		if($playerData->award->{$award_index})//已领奖
		{
			$returnData -> fail = 2;
			break;
		}
		
		$gameData = json_decode($sqlResult['game_data']);
		$count = 0;
		foreach($gameData->finish as $key=>$value)
		{
			if($value)
				$count ++;
		}
		$needCount = $award_index * 5;
		if($count < $needCount)//未达到
		{
			$returnData -> fail = 3;
			$returnData->pve_data = $gameData;
			break;
		}
		
		
		$playerData->award->{$award_index} = 1;
		$sql = "update ".$todayTable." set player".$player_index."='".json_encode($playerData)."' where id=".$team;
		$conne->uidRst($sql);
		
		
		$award = new stdClass();
		$returnData->award = $award;
		$collectNum = ceil(pow(1.5+$gameData->hard/2,1.6+$award_index/2));
		$award->coin = $collectNum * 100;
		
		$userData->addCoin($award->coin);
		$award->collect = addMonsterCollect($collectNum);
		
		
		$userData->write2DB();		
	}while(false);
?> 