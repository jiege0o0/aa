<?php 
	$id = $msg->id;
	$arr = array(
		'201'=>array('cost'=>12),
		'202'=>array('cost'=>12),
		'203'=>array('cost'=>12),
		
		'101'=>array('cost'=>6,'diamond'=>100),
		'102'=>array('cost'=>30,'diamond'=>520),
		'103'=>array('cost'=>150,'diamond'=>2650),
		'104'=>array('cost'=>600,'diamond'=>10650),
		'105'=>array('cost'=>1,'diamond'=>1)
	);
	
	
	do{
		if($id > 200)
		{
			if(!$userData->tec->vip)
				$userData->tec->vip = array();
			array_push($userData->tec->vip,$id);
			$userData->setChangeKey('tec');
			// $userData->tec->vip
			// $userData->energy->vip = 1;
			// $userData->setChangeKey('energy');
			// $returnData->sync_energy = $userData->energy;
		}
		else
		{
			$userData->addDiamond($arr[$id]['diamond'],true);
		}
		$userData->rmb += $arr[$id]['cost'];
		$userData->setChangeKey('rmb');
		$userData->write2DB();
		payLog(json_encode($msg));
		
		if($msg->order)
		{	
			$sql = "insert into ".$sql_table."pay_log(gameid,orderno,orderno2,time,goodsid) values('".$userData->gameid."','".$msg->order."','".$msg->localOrder."',".time().",'".$id."')";
			$conne->uidRst($sql);
		}
		
		
	}while(false);
?> 