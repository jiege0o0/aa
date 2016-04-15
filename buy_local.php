<?php 
	$id = $msg->id;
	$arr = array(
		'1'=>array('cost'=>60,'rmb'=>true),
		'2'=>array('cost'=>60,'rmb'=>false),
		'11'=>array('cost'=>60,'rmb'=>false,'level'=>0),
		'12'=>array('cost'=>210,'rmb'=>true,'level'=>10),
		'13'=>array('cost'=>600,'rmb'=>true,'level'=>30),
		'14'=>array('cost'=>1500,'rmb'=>true,'level'=>60)
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
		if($id == 1)
		{
			$userData->energy->vip = 1;
			$userData->setChangeKey('energy');
			$returnData->sync_energy = $userData->energy;
		}
		else if($id == 2)
		{
			$userData->addEnergy(30,true);
		}
		else
		{
			$level = $userData->main_game->level + $data['level'];
			require_once($filePath."main_award_fun.php");
		}
		
		$userData->write2DB();
	}while(false);
?> 