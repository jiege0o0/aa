<?php 
	do{
		$propNum = $userData->getPropNum(42);
		if($propNum < 10)
		{
			$returnData->fail = 1;
			$returnData->sync_prop = new stdClass();
			$returnData->sync_prop->{'42'} = $propNum;
			break;
		}
		$toPropNum = floor($propNum/10);
		$userData->addProp(42,-$toPropNum*10);
		$userData->addProp(41,$toPropNum);
		$returnData->num = $toPropNum ;
		
		
		$userData->write2DB();
	}while(false)	
	



?> 