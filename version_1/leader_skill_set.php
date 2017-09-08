<?php 
	$skillID = $msg->skillid;
	do{
		if(!$userData->tec->skill)
		{
			$returnData->fail = 1;
			break;
		}
		if($skillID && !in_array($skillID,$userData->tec->skill,true))
		{
			$returnData->fail = 2;
			break;
		}	
		$userData->tec->use_skill = $skillID;
		$userData->setChangeKey('tec');
		$userData->write2DB();
	}while(false)	
	



?> 