<?php 
	$ids = $msg->ids;
	do{
		$count = 0;
		foreach($ids as $key=>$value)
		{	
			if($userData->getCollectNum($key) < $value)
			{
				$returnData->fail = 1;
				if(!$returnData->sync_collect_num)
					$returnData->sync_collect_num = new stdClass();
				$returnData->sync_collect_num->{$key} = $userData->getCollectNum($key);
			}
			else
			{
				$userData->addCollect($key,-$value);
				$count += $value;
			}
		}
		if($returnData->fail)
			break;
		$returnData->num = $count*3;
		$userData->addProp(22,$count*3);
		$userData->write2DB();	
	}while(false);
	


?> 