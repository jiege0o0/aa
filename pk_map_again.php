<?php 
	do{
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 1;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		
		$userData->addEnergy(-1);
		$userData->write2DB();	
		
		$returnData->data = 'ok';
	}while(false);
		
?> 