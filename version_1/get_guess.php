<?php 
	do{
		if(!$userData->active->guess)
			$userData->active->guess = new stdClass();
		if($userData->active->guess->award)
		{
			$returnData->fail = 1;//已有数据
			$returnData->list1 = $userData->active->guess->list1;
			$returnData->list2 = $userData->active->guess->list2;
			$returnData->award = $userData->active->guess->award;
			break;
		}
		$level = $userData->level;
		$arr = array();
		for($i=0;$i<3 && $level > 0;$i++)
		{
			require_once($filePath."cache/guess_".$level.".php");
			$level --;
			$arr = array_merge($arr,$guess_base);
		}
		
		$len = count($arr);

		
		$userData->active->guess->list1 = $arr[rand(0,$len - 1)];
		$userData->active->guess->list2 = $arr[rand(0,$len - 1)];
		$award = new stdClass();
		$userData->active->guess->award = $award;
		
		if($userData->main_game->level > 95)
			$rate = rand(0,1000);
		else
			$rate = rand(0,800);
		
		// $rate = 900;
		if($rate < 300)//coin
		{
			$coin = round(pow(1.2,$userLevel+4)*50*(1+lcg_value()));
			$award->type = 'coin';
			$award->value = $coin;
		}
		else if($rate < 600)//card
		{
			$card = round(pow(1.2,$userLevel+3)*0.5*(1+lcg_value()));
			$award->type = 'card';
			$award->value = $card;
		}
		else if($rate < 700)//energy
		{
			$energy = round(3 + lcg_value()*3);
			$award->type = 'energy';
			$award->value = $energy;
		}
		else if($rate < 760)//修正
		{
			$pNum = round(1 + lcg_value()*2);
			$award->type = 'prop';
			$award->id = 21;
			$award->value = $pNum;
		}
		else if($rate < 820)//diamond
		{
			$diamond = round(5 + lcg_value()*5);
			$award->type = 'diamond';
			$award->value = $diamond;
		}
		else if($rate < 990)//命运石碎片
		{
			$pNum = rand(1,3);
			$award->type = 'prop';
			$award->id = 42;
			$award->value = $pNum;
		}
		else//初级卡
		{
			$pNum = 1;
			$award->type = 'prop';
			$award->id = 31;
			$award->value = $pNum;
			
		}

		
		$userData->setChangeKey('active');
		$userData->write2DB();	
		
		$returnData->list1 = $userData->active->guess->list1;
		$returnData->list2 = $userData->active->guess->list2;
		$returnData->award = $userData->active->guess->award;
	}while(false);
		
?> 