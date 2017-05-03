<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$myChoose = $msg->choose;
	$data_key = $msg->data_key;
	$team1Data = changePKData($myChoose,'map_game');
	do{
		
		if(property_exists($team1Data,'fail'))//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		
		$team2Data = new stdClass();
		$team2Data->list = $userData->pk_common->map->enemy->list;
		$team2Data->fight = $userData->pk_common->map->enemy->force;
		$currentLevel = $userData->pk_common->map->level;

		
	
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		
		//处更玩家数据,奖励
		$level = $userData->pk_common->map->enemy->level;
		$award = new stdClass();
		$returnData->award = $award;
		$award->exp = ceil(20*(1+$level/10));
		if($result)
		{
			$award->g_exp = $level * 2;
			$userData->pk_common->map->value += $award->g_exp;
			if($currentLevel == $level)
			{
				$userData->pk_common->map->step ++;
				if($userData->pk_common->map->step >= 10)
				{
					$userData->pk_common->map->step = 0;
					$userData->pk_common->map->sweep->{$level} = 10;
					$userData->pk_common->map->level ++;
				}
			}
			else
			{
				if(!isSameDate($userData->pk_common->map->lasttime))
				{
					$userData->pk_common->map->sweep = new StdClass();
				}
				if(!$userData->pk_common->map->sweep->{$level})
					$userData->pk_common->map->sweep->{$level} = 1;
				else
					$userData->pk_common->map->sweep->{$level} ++;
			}
			$userData->pk_common->map->lasttime = time();
			$userData->setChangeKey('pk_common');
		}
		else
		{
			$award->exp = 10 + floor($level/5);
		}
	
		$userData->addExp($award->exp);	
		renewMyCard();
		$userData->addHistory($team1Data->list);
		$userData->write2DB();	
		
	}while(false);
	
?> 