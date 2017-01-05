<?php 

	$id = $msg->headid;

	do{

		if($userData->head == $id)
		{
			$returnData->fail = 1;//头像不足
			break;
		}
		
		if($userData->getDiamond() < 100)
		{
			$returnData->fail = 2;//钻石不够
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		
		$userData->addDiamond(-100);
		$userData->head = $id;
		$userData->setChangeKey('head');
		$userData->write2DB();	

		$returnData->data = "OK";		
	}while(false);
	


?> 