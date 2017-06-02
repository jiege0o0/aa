<?php 
	$level = $msg->level;
	do{
		if($userData->pk_common->map->level <= $level)
		{
			$returnData->fail = 1;//没通关
			$returnData->level = $userData->pk_common->map->level;		
			break;
		}
		
		if(!isSameDate($userData->pk_common->map->lasttime))
		{
			$userData->pk_common->map->sweep = new StdClass();
		}
		$pktimes = $userData->pk_common->map->sweep->{$level};
		$maxPKTimes = min(10,$level + 2);
		if(!$pktimes)
			$pktimes = $maxPKTimes;
		else
			$pktimes = $maxPKTimes - $pktimes;
			
		if($pktimes <= 0)
		{
			$returnData->fail = 2;//扫荡完成
			break;
		}		
		
		
		if($userData->getDiamond() < $pktimes)//钻石不足
		{	
			$returnData->fail = 3;
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		
		$value = $level * 2 * $pktimes;
		$userData->pk_common->map->sweep->{$level} = $maxPKTimes;
		$userData->pk_common->map->value += $value;
		$userData->pk_common->map->lasttime = time();
		$userData->setChangeKey('pk_common');
		$userData->addDiamond(-$pktimes);
		$userData->write2DB();	
		$returnData->value = $value;		
	}while(false);
	


?> 