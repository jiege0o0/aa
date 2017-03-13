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
		
		$pkUserInfo = new stdClass();
		$pkUserInfo->level = $level;
		
		
		$team2Data = new stdClass();
		$team2Data->list = $main_game[$level]['list'];
		$team2Data->ring = new stdClass();
		$team2Data->ring->id = $main_game[$level]['ring'];
		$team2Data->ring->level = ceil($level/100);
		$team2Data->fight = getMainForce($level);
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
		$award->exp = ceil(20*(1+$level/50));
		$award->coin = ceil(50*(1+$level/200));
		if($result)
		{
			if($userData->exp == 0 && $userData->level == 1)//新手副利
			{
				$award->coin = 1000;
				require_once($filePath."get_monster_collect.php");
				$award->collect = addMonsterCollect(10);
			}
			$userData->main_game->level++;
			$userData->main_game->time = time();
			$userData->main_game->kill = array();
			$returnData->sync_main_game->kill = array();
			$returnData->sync_main_game->level = $userData->main_game->level;
		}
		else
		{
			$award->exp = 10 + floor($level/50);
			$award->coin = 0;
		}

		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->main_game->choose = null;
		$userData->main_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK,"info"=>$pkUserInfo,'version'=>$pk_version);
		$returnData->sync_main_game->choose = null;
		
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('main_game');
		$userData->write2DB();		

		
	}while(false);
	
	function getMainForce($level){
		$add = $level;
		$index = 1;
		while($level > 50*$index)
		{
			$add += ($level - 50*$index);
			$index ++;	
		}
		return $add;
	}
	


?> 