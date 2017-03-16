<?php 
	require_once($filePath."get_monster_collect.php");
	$id = $msg->id;
	$arr = array(
		'2'=>array('cost'=>60,'rmb'=>false),
		
		'11'=>array('cost'=>60,'rmb'=>false,'rate'=>1),
		'12'=>array('cost'=>300,'rmb'=>false,'rate'=>6),
		'13'=>array('cost'=>1500,'rmb'=>false,'rate'=>31),
		
		'21'=>array('cost'=>60,'rmb'=>false,'rate'=>1),
		'22'=>array('cost'=>300,'rmb'=>false,'rate'=>6),
		'23'=>array('cost'=>1500,'rmb'=>false,'rate'=>31),
		
		'31'=>array('cost'=>60,'rmb'=>false,'rate'=>5),
		'32'=>array('cost'=>300,'rmb'=>false,'rate'=>30)
	);
	
	do{
		$data = $arr[$id];
		if($userData->getDiamond($data['rmb']) < $data['cost'])
		{
			$returnData->fail = 1;//Ç®²»¹»
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		$userData->addDiamond(-$data['cost']);
		$userLevel = $userData->level;
		if($id == 2)
		{
			$userData->addEnergy(48);
		}
		else if($id >10 && $id < 20 )
		{
			$rate = $data['rate'];
			$award = new stdClass();
			$returnData->award = $award;
			$award->coin = round(pow(1.2,$userLevel+5)*1000*$rate);
			$userData->addCoin($award->coin);
		}
		else if($id >20 && $id < 30)
		{
			$award = new stdClass();
			$returnData->award = $award;
			// $userLevel = $userData->level;
			$rate = $data['rate'];
			// $exp = floor(150*(1+$userLevel/50))*$rate;
			$collectNum = round(pow(1.2,$userLevel+5)*10*$rate);

			// $award->exp = $exp;
			$award->collect = addMonsterCollect($collectNum);
			// $userData->addExp($award->exp);
		}
		else if($id >30 && $id < 40 )
		{
			$rate = $data['rate'];
			$award = new stdClass();
			$returnData->award = $award;
			$award->prop = new stdClass();
			$award->prop->{21} = $rate;	
			$userData->addProp(21,$rate);
		}
		
		$userData->write2DB();
		payLog(json_encode($msg));
	}while(false);
?> 