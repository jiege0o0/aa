<?php 
	do{

		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 1;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		
		require_once($filePath."random_fight_card.php");
		$oo = new stdClass();
		$oo->list = randomFightCard(ceil($msg->level/2));
		$begin = ceil(pow($msg->level,2.2));
		$end = $begin + ceil(pow($msg->level,1.2));
		$oo->force = rand($begin,$end);
		$oo->level = $msg->level; 
		
		if($userData->pk_common->map == null)
		{
			$userData->pk_common->map = new stdClass();
			$userData->pk_common->map->value = 0;
			$userData->pk_common->map->level = 1;
			$userData->pk_common->map->step = 0;
			$userData->pk_common->map->lasttime = time();
			$userData->pk_common->map->sweep = new stdClass();
		}
		$userData->pk_common->map->enemy = $oo;
		
		$userData->addEnergy(-1);
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		
		$returnData->data = $oo;
	}while(false);
		
?> 