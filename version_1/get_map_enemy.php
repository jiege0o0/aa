<?php 
	do{
		require_once($filePath."map_code.php");
		
		if(!$userData->pk_common->map->pk_value)//PK次数不足
		{
			resetMapCD();
			if(!$userData->pk_common->map->pk_value)
			{
				$returnData->fail = 1;
				break;
			}
		}
		
		$level = $userData->pk_common->map->level;
		require_once($filePath."random_fight_card.php");
		$oo = new stdClass();
		$oo->list = randomFightCard(ceil($level));
		$force = ceil(pow($level,1.6))*25 - 24;
		$oo->force = $force + rand(0,$level*9);
		$oo->level = $level; 
		
		$userData->pk_common->map->pk_value --;
		$userData->pk_common->map->enemy = $oo;
		
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		
		$returnData->data = $oo;
	}while(false);
		
?> 