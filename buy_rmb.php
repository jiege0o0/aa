<?php 
	$id = $msg->id;
	$arr = array(
		'21'=>array('cost'=>6,'diamond'=>60),
		'22'=>array('cost'=>30,'diamond'=>305),
		'23'=>array('cost'=>50,'diamond'=>520),
		'24'=>array('cost'=>100,'diamond'=>1050),
		'25'=>array('cost'=>300,'diamond'=>3200)
	);
	
	
	do{
		$userData->addDiamond($arr[$id]['diamond'],true);
		$userData->write2DB();
	}while(false);
?> 