<?php 
	$num = $msg->num;
	do{
		if($userData->getDiamond() < 5*$num)//钻石不够
		{
			$returnData->fail = 1;//钻石不够
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		if(!$userData->pk_common->pk_jump)
			$userData->pk_common->pk_jump = 0;
		$userData->pk_common->pk_jump += 20*$num;
		$userData->setChangeKey('pk_common');
		$userData->addDiamond(-5*$num);
		$userData->write2DB();	
		$returnData->data = 'ok';
	}while(false);
		
?> 