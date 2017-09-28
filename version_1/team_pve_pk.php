<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$level = $msg->level;
	$player_index = $msg->player_index;
	$myChoose = $msg->choose;
	
	do{
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 4;
			$returnData->sync_energy = $userData->energy;
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
		
		if($playerData->pk_time >= 5*$playerData->buy_time + 10)//次数已用完
		{	
			$returnData -> fail = 2;
			break;
		}
		
		$gameData = json_decode($sqlResult['game_data']);
		if($gameData->finish->{$level})//已被打
		{
			$returnData -> fail = 3;
			$returnData->pve_data = $gameData;
			break;
		}
		
		
		$team1Data = changePKData($myChoose,'my_card',false,null,$gameData->hard);
		if(property_exists($team1Data,'fail'))//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$name = 'pve'.$month."_".$day."_".$gameData->hard;
		$file  = $dataFilePath.'dungeon_game/'.$name.'.txt';
		$content = json_decode(file_get_contents($file))->levels;
		
		
		
		$team2Data = new stdClass();
		$team2Data->list = $content[$level-1]->list;
		$forceArr = $HardBase['force'];
		$team2Data->fight = $forceArr[$gameData->hard] + floor((pow($gameData->hard,0) - 0.5)*($level - 1));
		resetTeam2Data();
		if($content[$level-1]->skill)
			$team2Data->skill = $content[$level-1]->skill;


		
	
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		
		if($result)
		{
			$award->exp = ceil(20*($gameData->hard+$level/10));
			$award->coin = ceil(30*($gameData->hard+$level/10));
		}
		else
		{
			$award->exp = 5 + $gameData->hard*5;
		}
		$userData->setChangeKey('pk_common');
		$userData->addExp($award->exp);	
		if($award->coin)
			$userData->addCoin($award->coin);	
			
		$userData->addEnergy(-1);
		renewMyCard();
		$userData->addHistory($team1Data->list);
		$userData->write2DB();	
		
		
		$playerData->pk_time ++;
		if($result)
		{
			$gameData->finish->{$level} = $player_index;
			$sql = "update ".$todayTable." set player".$player_index."='".json_encode($playerData)."',game_data='".json_encode($gameData)."' where id=".$userData->active->team_pve->team;
		}
		else
		{
			$sql = "update ".$todayTable." set player".$player_index."='".json_encode($playerData)."' where id=".$userData->active->team_pve->team;
		}
		$conne->uidRst($sql);
		$returnData->pve_data = $gameData;
		
	}while(false);
	
?> 