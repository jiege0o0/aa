<?php 
	//取聊天信息
	$time = $msg->time;
	$monster = $msg->monster;
	do{
		$sql = "select * from monster_star where id =".$monster."";
		$returnData->star = $conne->getRowsRst($sql);
	
		$sql = "select * from monster_talk_".$monster." where time >".$time."";
		$returnData->talk = $conne->getRowsArray($sql);
	}
	while(false);
	
	
	
		
?> 