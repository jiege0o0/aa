<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$myChoose = $msg->choose;
	$team1Data = changePKData($myChoose,'my_card');
	do{
		
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 2;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		
		if(property_exists($team1Data,'fail'))//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		if(!$isFromBack)//不是从报复进来
		{
			if(!$userData->pk_common->map->get_fight_enemy)
			{
				$returnData -> fail = 1;
				break;
			}
			$fightEnemy = $userData->pk_common->map->get_fight_enemy;
			$level = $fightEnemy->level;
			if($fightEnemy->gameid == 'npc')
			{
				require_once($filePath."random_fight_card.php");
				$tempArr = randomFightCard(ceil($level));	
				$team2Data = new stdClass();
				$team2Data->list = $tempArr['list'];
				$team2Data->fight = ceil(pow($level,1.6))*25 - 24 + rand(0,$level*9);
				resetTeam2Data();
			}
			else
			{
				$sql = "select pk_common,public_value from ".$sql_table."user_data where gameid='".$fightEnemy->gameid."'";
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
				$level = $pkComment->map->level;
				$team2Data = $pkComment->map->last_pk_data;
			}
		}
		
		

		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		
		//处更玩家数据,奖励
		
		$award = new stdClass();
		$returnData->award = $award;
		$award->exp = ceil(20*(1+$level/10));
		if($result)
		{
			$currentAward = floor(2 + $userData->pk_common->map->level*1.2);
			$maxAward = $currentAward *(120);
			$award->g_exp = floor($maxAward/6);
			$userData->pk_common->map->value += $award->g_exp;
			
			//更新对方的数据
			if($otherResult)
			{
				if($otherResult['public_value'])
					$public_value = json_decode($otherResult['public_value']);
				else 
					$public_value = new stdClass();
				if(!$public_value->map)	
					$public_value->map = new stdClass();
				
				if($public_value->map->value)
					$public_value->map->value += $award->g_exp;
				else
					$public_value->map->value = $award->g_exp;
					
				$sql = "update ".$sql_table."user_data set public_value='".json_encode($public_value)."' where gameid='".$fightEnemy->gameid."'";
				$conne->uidRst($sql);
			}
		}
		else
		{
			$award->exp = 10 + floor($level/5);
		}
		
		//写日志
		if($otherResult)
		{
			$oo = new stdClass();
			$oo->result = $result;
			$oo->value = $award->g_exp;
			$oo->from_nick = base64_encode($userData->nick);
			$oo->to_nick = $fightEnemy->nick;
			$oo->team1 = $team1Data;
			$oo->team2 = $team2Data;
			$oo->pk_version = $pk_version;
			
			$type = 0;
			if(!$result)
				$type = 1;
			$sql = "insert into ".$sql_table."map_fight_log(from_gameid,to_gameid,type,content,time) values('".$userData->gameid."','".$fightEnemy->gameid."',".$type.",'".json_encode($oo)."',".time().")";
			if(!$conne->uidRst($sql))
			{
				$returnData->fail = 5;
				break;
			}
		}

		
		$userData->pk_common->map->last_pk_data = $team1Data;
		if(!$isFromBack)
			$userData->pk_common->map->get_fight_enemy = null;
		
		if(!isSameDate($userData->pk_common->map->get_fight_time))
			$userData->pk_common->map->fight_times = 0;
		$userData->pk_common->map->fight_times ++;
		require_once($filePath."map_add_fight.php");
		
		$userData->setChangeKey('pk_common');
		$userData->addExp($award->exp);	
		renewMyCard();
		$userData->addHistory($team1Data->list);
		$userData->addEnergy(-1);
		$userData->write2DB();		
	}while(false);
	
?> 