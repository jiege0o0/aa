<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$myChoose = $msg->choose;
	debug($myChoose);
	$team1Data = changePKData($myChoose,'main_game');
	do{
		if(property_exists($team1Data,'fail'))//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$level = $userData->main_game->level;
		require_once($filePath."cache/main_game".ceil($level/100).".php");
		
		
		$team2Data = new stdClass();
		$team2Data->list = $main_game[$level]['list'];
		$team2Data->ring = new stdClass();
		$team2Data->ring->id = $main_game[$level]['ring'];
		$team2Data->ring->level = ceil($level/100);
		$team2Data->fight = $level*2;
		foreach($userData->main_game->kill as $key=>$value)
		{
			$team2Data->list[$value] = 0;
		}
		
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		$returnData->sync_main_game = new stdClass();
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		$award->prop = new stdClass();
		$award->exp = 30*ceil($level/10);
		$award->coin = 100*ceil($level/10);
		if($result)
		{
			$userData->main_game->level++;
			$userData->main_game->time = time();
			$userData->main_game->kill = array();
			$returnData->sync_main_game->kill = array();
			$returnData->sync_main_game->level = $userData->main_game->level;
		}
		else
		{
			$award->exp = 20;
			$award->coin = 50;
		}

		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->main_game->choose = null;
		$userData->main_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK);
		$returnData->sync_main_game->choose = null;
		
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('main_game');
		$userData->write2DB();		

		
	}while(false);
	


?> 