<?php 
	$id = $msg->id;
	$arr = array(
		'101'=>array('cost'=>6,'diamond'=>60),
		'102'=>array('cost'=>30,'diamond'=>305),
		'103'=>array('cost'=>50,'diamond'=>520),
		'104'=>array('cost'=>100,'diamond'=>1050),
		'105'=>array('cost'=>300,'diamond'=>3200)
	);
	
	
	do{
		$userData->addDiamond($arr[$id]['diamond'],true);
		$userData->write2DB();
	}while(false);
?> 