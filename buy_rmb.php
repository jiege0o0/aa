<?php 
	$id = $msg->id;
	$arr = array(
		'1'=>array('cost'=>12),
		'101'=>array('cost'=>6,'diamond'=>100),
		'102'=>array('cost'=>30,'diamond'=>520),
		'103'=>array('cost'=>150,'diamond'=>2650),
		'104'=>array('cost'=>600,'diamond'=>10650)
	);
	
	
	do{
		if($id == 1)
		{
			$userData->energy->vip = 1;
			$userData->setChangeKey('energy');
			$returnData->sync_energy = $userData->energy;
		}
		else
		{
			$userData->addDiamond($arr[$id]['diamond'],true);
		}
		$userData->write2DB();
		payLog(json_encode($msg));
		
	}while(false);
?> 