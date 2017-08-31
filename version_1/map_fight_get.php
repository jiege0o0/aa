<?php 
	do{		
		if(!$userData->pk_common->map->get_fight_time || !isSameDate($userData->pk_common->map->get_fight_time))
			$userData->pk_common->map->fight_times = 0;
			
		if($userData->pk_common->map->fight_times >= 10)
		{
			$returnData->fail = 1;
			break;
		}
			
			
		$level = $userData->pk_common->map->level;
		$tableName = $sql_table."map_fight";
		$sql = "select * from ".$tableName." where gameid!='".$userData->gameid."' and level=".$level." and time>0 order by time asc limit 1";
		$result = $conne->getRowsRst($sql);

		$enemyData = new stdClass();
		$enemyData->level = $level; 
		if(!$result)//没数据，随机一个NPC打
		{

			// require_once($filePath."random_fight_card.php");
			// require_once($filePath."pk_action/pk_tool.php");
			// $tempArr = randomFightCard(ceil($level));	
			
			// $team2Data = new stdClass();
			// $team2Data->list = $tempArr['list'];
			// $team2Data->fight = ceil(pow($level,1.6))*25 - 24 + rand(0,$level*9);
			// resetTeam2Data();
			$enemyData->gameid = 'npc';
		}
		else
		{
			$enemyData->gameid = $result['gameid'];
			$saveData = json_decode($result['content']);
			$enemyData->head = $saveData->head;
			$enemyData->nick = $saveData->nick;
			
			
			$sql = "update ".$tableName." set time=0 where id=".$result['id'];
			$conne->uidRst($sql);
			
		}
		
		require_once($filePath."map_add_fight.php");
		
		
		$userData->pk_common->map->get_fight_time = time();
		$userData->pk_common->map->get_fight_enemy = $enemyData;
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	

		$returnData->data = $userData->pk_common->map->get_fight_enemy;
	}while(false);
	


?> 