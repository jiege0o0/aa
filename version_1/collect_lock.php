<?php 
	$id = $msg->id;	
	$islock = $msg->islock;	
	do{
		$b = in_array($id,$userData->collect->lock,true);
		if(!$islock && $b)
		{
		$index = array_search($id,$userData->collect->lock);
			array_splice($userData->collect->lock,$index,1);
			$userData->setChangeKey('collect');
		}
		else if($islock && !$b)
		{
			array_push($userData->collect->lock,$id);
			$userData->setChangeKey('collect');
		}
		$userData->write2DB();	
		$returnData->data = "OK";		
	}while(false);
	


?> 