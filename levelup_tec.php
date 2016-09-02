<?php 
	//升级科技等级
	$type=$msg->type;//类型
	$id=$msg->id;//类型中的哪个科技
	//判断资源够不够
	//3级以下不要道具
	//20级以下不要高级道具
	$lastLevel = $userData->tec->{$type}->{$id};
	
	if(!$lastLevel)
		$lastLevel = 0;
	do{
		$maxLevel = 30;//最大升级等级
		$level = $lastLevel+1;
		if($level>$maxLevel)
		{
			$returnData->fail = 4;
			$returnData->{'sync_tec_'.$type} = new stdClass();
			$returnData->{'sync_tec_'.$type}->{$id} = $userData->tec->{$type}->{$id};
			break;
		}
		
		
		$coin = floor(pow($level,2.2)*100);
		if($userData->coin < $coin)//钱不够
		{
			$returnData->fail = 1;
			$returnData->sync_coin = $userData->coin;
			break;
		}
		
		$needNum = floor(pow(1.25,$level)*$level);
		$mNum = $userData->getCollectNum($id);
		if($needNum > $mNum)//碎片不够
		{
			$returnData->fail = 2;
			$returnData->sync_collect_num = new stdClass();
			$returnData->sync_collect_num->{$id} = $mNum;
			break;
		}
		
		// $propNum = 0;
		// if($level > 2)
			// $propNum = $level - 1;//首次2个
		// if($propNum > 0)
		// {
			// $propID = 0;
			// if($type == 'main')
				// $propID = 1;
			// else if($type == 'ring')
				// $propID = 2;
			// else if($type == 'monster')
				// $propID = 3;
			// if($userData->getPropNum($propID) < $propNum)//低级道具不够
			// {
				// $returnData->fail = 2;
				// $returnData->sync_prop = new stdClass();
				// $returnData->sync_prop->{$propID} = $userData->prop->{$propID};
				// break;
			// }
		// }

		// $propNum2 = 0;
		// if($level > 19)
			// $propNum2 = $level - 19;//首次1个
		// if($propNum2 > 0)
		// {
			// $propID2 = 0;
			// if($type == 'main')
				// $propID2 = 11;
			// else if($type == 'ring')
				// $propID2 = 12;
			// else if($type == 'monster')
				// $propID2 = 13;
			// if($userData->getPropNum($propID2) < $propNum2)//高级道具不够
			// {
				// $returnData->fail = 3;
				// $returnData->sync_prop = new stdClass();
				// $returnData->sync_prop->{$propID2} = $userData->prop->{$propID2};
				// break;
			// }
		// }
		
		//可以升级了
		$userData->addCoin(-$coin);
		$userData->levelUpTec($type,$id);
		$userData->write2DB();
		$returnData->{'sync_tec_'.$type} = new stdClass();
		$returnData->{'sync_tec_'.$type}->{$id} = $userData->tec->{$type}->{$id};
		
	}while(false)	
	



?> 