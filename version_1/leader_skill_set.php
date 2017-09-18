<?php 
	$skillID = $msg->skillid;
	do{
		
		if(!$userData->tec->skill)
		{
			$userData->tec->skill = array();
		}
		if(!$userData->tec->copy_skill)
		{
			$userData->tec->copy_skill = new stdClass();
		}
		
		if($skillID && (!in_array($skillID,$userData->tec->skill,true) && !$userData->tec->copy_skill->{$skillID} ))
		{
			$returnData->fail = 2;
			break;
		}	
		$userData->tec->use_skill = $skillID;
		$userData->setChangeKey('tec');
		$userData->write2DB();
	}while(false)	
	



?> 