<?php 
	$id = $msg->id;
	$arr = array(
		'1'=>array('cost'=>60,'rmb'=>true),
		'2'=>array('cost'=>60,'rmb'=>false),
		
		'11'=>array('cost'=>60,'rmb'=>false,'rate'=>1),
		'12'=>array('cost'=>520,'rmb'=>true,'rate'=>9),
		'13'=>array('cost'=>2880,'rmb'=>true,'rate'=>50),
		
		'21'=>array('cost'=>60,'rmb'=>false,'rate'=>1),
		'22'=>array('cost'=>520,'rmb'=>true,'rate'=>9),
		'23'=>array('cost'=>2880,'rmb'=>true,'rate'=>50),
		
		'31'=>array('cost'=>60,'rmb'=>false,'rate'=>5),
		'32'=>array('cost'=>1050,'rmb'=>true,'rate'=>88)
	);
	
	do{
		$data = $arr[$id];
		if($userData->getDiamond($data['rmb']) < $data['cost'])
		{
			$returnData->fail = 1;//Ç®²»¹»
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		$userData->addDiamond(-$data['cost'],$data['rmb']);
		if($id == 1)
		{
			$userData->energy->vip = 1;
			$userData->setChangeKey('energy');
			$returnData->sync_energy = $userData->energy;
		}
		else if($id == 2)
		{
			$userData->addEnergy(48,true);
		}
		else if($id >10 && $id < 20 )
		{
			$level = $userData->main_game->level;
			$rate = $data['rate']
			require_once($filePath."main_award_fun.php");
		}
		else if($id >20 && $id < 30)
		{
			$award = new stdClass();
			$returnData->award = $award;
			$userLevel = $userData->level;
			$rate = $data['rate'];
			$exp = floor(150*(1+$userLevel/50))*$rate;
			$collectNum = floor(pow(1.3 + ($userLevel+10)/100,10)*1)*$rate;

			$award->exp = $exp;
			$award->collect = addMonsterCollect($collectNum,3+floor($rate/5));
			$userData->addExp($award->exp);
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
	}while(false);
?> 