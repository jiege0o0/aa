<?php 
	require_once($filePath."cache/monster.php");
	$type=$msg->type;//类型
	
	do{
		if(!$userData->tec->leader)
			$userData->tec->leader = new stdClass();
		if($userData->tec->leader->list)
		{
			$returnData->fail = 3;
			$returnData->list = $userData->tec->leader->list;
			break;
		}
		$free = false;
		if($type == 1)
		{
			if(!$userData->tec->leader->lasttime || !isSameDate($userData->tec->leader->lasttime))
			{
				$free = true;
				$returnData->isFree = true;
				$userData->tec->leader->lasttime = time();
			}
			if(!$free)
			{
				$propNum = $userData->getPropNum(31);
				if($propNum == 0)
				{
					$returnData->fail = 1;
					$returnData->sync_prop = new stdClass();
					$returnData->sync_prop->{'31'} = 0;
					break;
				}
				$userData->addProp(31,-1);
			}
		}
		else
		{
			$propNum = $userData->getPropNum(32);
			if($propNum)
			{
				$userData->addProp(32,-1);
			}
			else
			{
				$cost = 500;
				if($userData->getDiamond() < $cost)
				{
					$returnData->fail = 2;
					$returnData->sync_diamond = $userData->diamond;
					break;
				}
				$userData->addDiamond(-$cost);
			}
		}
		
		//随机
		$userLevel = $userData->level;
		$levelArr = array();
		foreach($monster_base as $key=>$value)
		{
			if($userLevel >= $value['level'])
			{	
				array_push($levelArr,$key);
			}
		}
		usort($levelArr,randomSortFun);
		
		if($type==1)
			$returnArr = array_slice($levelArr,0,2);
		else
			$returnArr = array_slice($levelArr,0,6);
			
		$userData->tec->leader->list = $returnArr;	
		$returnData->list = $returnArr;	
		$userData->setChangeKey('tec');
		$userData->write2DB();

		
	}while(false)	
	



?> 