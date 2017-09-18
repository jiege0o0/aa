<?php 
	do{
		$coin = 0;
		$propNum = 0;
		if($userData->public_value->skill)
		{
			$coin += $userData->public_value->skill->prop*100;
			$propNum += $userData->public_value->skill->diamond;
			if($coin)
			{
				$userData->public_value->skill->prop = 0;
				$userData->addCoin($coin);
			}
			if($propNum)
			{
				$userData->public_value->skill->diamond = 0;
				$userData->addProp(42,$propNum);
			}	
			$userData->setChangeKey('public_value');			
			$userData->write2DB();	
		}
		$returnData->coin = $coin;
		$returnData->propnum = $propNum;
	}while(false);
?> 