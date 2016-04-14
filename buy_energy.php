<?php 
	$need = 60;
	$num = 30;
	do{
		if($userData->getDiamond() < $need)
		{
			$returnData->fail = 1;//Ç®²»¹»
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		$userData->addDiamond(-$need);
		$userData->addEnergy($num,true);
		
		$userData->write2DB();
	}while(false);
?> 