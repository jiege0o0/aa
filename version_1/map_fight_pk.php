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
		
		if(!$userData->pk_common->map->get_fight_enemy)
		{
			$returnData -> fail = 1;
			break;
		}
		
		$level = $userData->pk_common->map->get_fight_enemy->level;
		if($userData->pk_common->map->get_fight_enemy->gameid == 'npc')
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
			$sql = "select pk_common from ".$sql_table."user_data where gameid='".$userData->pk_common->map->get_fight_enemy->gameid."'";
			$result = $conne->getRowsRst($sql);
			if(!$result)
			{
				$returnData -> fail = 2;
				break;
			}
			$result['pk_common'];
			$team2Data = ;
		}
		
		
		
		$currentLevel = $userData->pk_common->map->level;
		
		$level = $userData->pk_common->map->enemy->level;
		
		if($currentLevel != $level)//通辑令与当前关卡不对
{
			$returnData -> fail = 3;
			break;
		}
		
	
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		
		//处更玩家数据,奖励
		
		$award = new stdClass();
		$returnData->award = $award;
		$award->exp = ceil(20*(1+$level/10));
		if($result)
		{

			$award->g_exp = ceil(pow($level,1.2)) + 4;
			$userData->pk_common->map->value += $award->g_exp;
			
			if($currentLevel == $userData->pk_common->map->max_level)
				$userData->pk_common->map->step ++;
		}
		else
		{
			$award->exp = 10 + floor($level/5);
		}
		$userData->pk_common->map->enemy->is_pk = true;
		$userData->pk_common->map->last_pk_data = $team1Data;
		
		require_once($filePath."map_add_fight.php");
		
		$userData->setChangeKey('pk_common');
		$userData->addExp($award->exp);	
		renewMyCard();
		$userData->addHistory($team1Data->list);
		$userData->addEnergy(-1);
		$userData->write2DB();	
		
	}while(false);
	
?> 