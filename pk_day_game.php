<?php 
	$file  = $filePath.'day_game/server'.$serverID.'/game'.date('Ymd', time()).'.txt';
	do{
		$level = $msg->level;
		if(!is_file($file))//文件未生成
		{
			$returnData->fail = 50;					
			break;
		}
		
		if(!isSameDate($userData->day_game->lasttime))
		{
			$userData->day_game->level = 0;
			$userData->day_game->lasttime = time();
		}
		if($level != $userData->day_game->level + 1)//不是打这关
		{
			$returnData->fail = 51;	
			$returnData->sync_day_game = new stdClass();
			$returnData->sync_day_game->level = $userData->day_game->level;				
			break;
		}
		if($level >10)//已完成
		{
			$returnData->fail = 52;	
			$returnData->sync_day_game = new stdClass();
			$returnData->sync_day_game->level = $userData->day_game->level;				
			break;
		}
		
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 53;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		
		$content = file_get_contents($file);
		$content = json_decode($content);
		$fromList = array($content->choose);
		
		require_once($filePath."pk_action/change_fight_data.php");
		require_once($filePath."pk_action/pk_tool.php");

		$myChoose = $msg->choose;
		$team1Data = changePKData($myChoose,'day_gamse',true,$fromList);
		if($team1Data->fail)//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$team2Data = $content->levels[$level-1];
		if(!$team2Data->fight)
			$team2Data->fight = 0;
		$team2Data->fight += ($level-1)*9;
		$equalPK = true;
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		$returnData->sync_day_game = new stdClass();
		
		$award = new stdClass();
		$returnData->award = $award;
		$award->prop = new stdClass();
		if($result)
		{
			$userData->day_game->level ++;
			$returnData->sync_day_game->level = $userData->day_game->level;
			$award->exp = $userData->day_game->level*30;
			$award->coin = $userData->day_game->level*100;
			
			$propNum = ceil(($userData->day_game->level - 3)/2);
			while($propNum > 0)
			{
				if(lcg_value()>0.33)
					tempAddProp(1);
				else if(lcg_value()>0.5)
					tempAddProp(2);
				else
					tempAddProp(3);
				$propNum -- ;
			}
			
			$propNum = ($userData->day_game->level - 8);
			while($propNum > 0)
			{
				if(lcg_value()>0.33)
					tempAddProp(11);
				else if(lcg_value()>0.5)
					tempAddProp(12);
				else
					tempAddProp(13);
				$propNum -- ;
			}
			
			if($userData->day_game->level >= 5 && $userData->day_game->level%2 == 1)
			{
				tempAddProp(21);
			}
			
			if($userData->day_game->level == 10)
				$userData->day_game->times ++;
		}
		else
		{				
			$award->exp = 25;
			$award->coin = 0;
		}
		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->addEnergy(-1);
		$userData->day_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK);
		
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('day_game');
		$userData->write2DB();		
		
		
	}while(false);
	
	function tempAddProp($id,$num=1){
		global $award;
		if($award->prop->{$id})
			$award->prop->{$id} += $num;
		else 
			$award->prop->{$id} = $num;		
	}
?> 