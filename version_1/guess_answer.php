<?php 
	do{
		$type = $msg->type;
		$num = $msg->num;
		$iswin = $msg->iswin;
		
		
		if(!$userData->active->guess)
		{
			$userData->active->guess = new stdClass();
			$userData->active->guess->num = 0;
			$userData->active->guess->win = 0;
			$userData->active->guess->total = 0;
		}
		if(!$userData->active->guess->award)
		{
			$returnData->fail = 3;//无数据
			break;
		}
		
		$maxNum = floor($userData->level/3) + 3;
		if($userData->active->guess->lasttime && !isSameDate($userData->active->guess->lasttime))
			$userData->active->guess->num = 0;
		if($userData->active->guess->num && $userData->active->guess->num >= $maxNum)
		{
			$returnData->fail = 4;//次数不足
			break;
		}

		
		// if($type == 'coin' && $userData->coin < $num)//$不足
		// {	
			// $returnData->fail = 1;
			// $returnData->sync_coin = $userData->coin;
			// break;
		// }
		
		
		// $mNum = $userData->getCollectNum($id);
		// if($type == 'card' && $mNum < $num)//卡不足
		// {	
			// $returnData->fail = 2;
			// $returnData->sync_collect_num = new stdClass();
			// $returnData->sync_collect_num->{0} = $mNum;
			// break;
		// }
		
		$equalPK = true;
		$team1Data = new stdClass();
		$team1Data->list = $userData->active->guess->list1;
		$team2Data = new stdClass();
		$team2Data->list = $userData->active->guess->list2;
		require_once($filePath."pk_action/pk.php");

		$returnAward = new stdClass();
		$returnData->award = $returnAward;
		
		if($iswin == $result)//猜中
		{
			$userData->active->guess->win ++;
			$award = $userData->active->guess->award;
			 switch($award->type)
			 {
				 case 'coin':
					$returnAward->coin = $award->value;
					 $userData->addCoin($award->value);
					 break;
				 case 'card':
					$returnAward->card = $award->value;
					 $userData->addCollect(0,$award->value);
					 break;
				 case 'energy':
					$returnAward->energy = $award->value;
					 $userData->addEnergy($award->value);
					 break;
				 case 'diamond':
					$returnAward->diamond = $award->value;
					 $userData->addDiamond($award->value);
					 break;
				 case 'prop':
					$returnAward->prop = new stdClass();
					$returnAward->prop->{$award->id} = $award->value;
					$userData->addProp($award->id,$award->value);
					break;
					
			 }
		}
			
		
		if($type == 'coin')
			$userData->addCoin($num);
		else
			$userData->addCollect(0,$num);
			
			
		$userData->active->guess->lasttime = time();
		$userData->active->guess->num ++;
		$userData->active->guess->total ++;
		$userData->active->guess->list1 = null;
		$userData->active->guess->list2 = null;
		$userData->active->guess->award = null;

		$userData->setChangeKey('active');
		$userData->write2DB();	
		
		$returnData->guess_win = $num > 0;
	}while(false);
		
?> 