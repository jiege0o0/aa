<?php 
	$type = $msg->type;
	$value = $msg->value;
	do{
		if($userData->pk_common->map->value < $value)//不足
		{	
			$returnData->fail = 1;
			$returnData->value = $userData->pk_common->map->value;
			break;
		}
		
		if($type == 1)//换金币
		{
			$returnData->coin = $value*2;
			$userData->addCoin($returnData->coin);
		}
		else if($type == 2)//换card
		{
			if($value % 50 != 0)//不对
			{	
				$returnData->fail = 2;
				break;
			}
			require_once($filePath."get_monster_collect.php");
			$returnData->card = floor($value/50);
			addMonsterCollect($returnData->card);
		}
		
		$userData->addTaskStat('map_buy');
		$userData->pk_common->map->value -= $value;
		require_once($filePath."map_add_fight.php");
		
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = "OK";		
	}while(false);
	


?> 