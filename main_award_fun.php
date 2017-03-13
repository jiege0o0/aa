<?php 

	require_once($filePath."get_monster_collect.php");
	$award = new stdClass();
	$returnData->award = $award;
	
	$award->coin = $level*100*$rate;
	$userData->addCoin($award->coin);
	
	//每过30小关奖一个碎片
	$propNum = (floor($level/30) + 1)*$rate;
	$award->collect = addMonsterCollect($propNum);//,4+$rate
	

?> 