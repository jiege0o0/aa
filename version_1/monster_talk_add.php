<?php 
	//加评论
	$talk = $msg->talk;
	$monster = $msg->monster;
	do{
		
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 1;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		
		if($talk == '没有什么看法，就是做个任务')
		{
			$userData->addTaskStat('comment');
			$userData->addEnergy(-1);
			$returnData->id = -1;
			$userData->write2DB();
			break;
		}
		
		$oo = new stdClass();
		$oo->head = $userData->head;
		$oo->gameid = $userData->gameid;
		$oo->nick = base64_encode($userData->nick);
		$oo->talk = base64_encode($talk);
		$oo->serverid = $serverID;
		$oo = json_encode($oo);
		
		$sql = "select id from monster_talk_".$monster." order by time asc limit 1";
		$result = $conne->getRowsRst($sql);
		if(!$result)
		{
			$returnData->fail = 2;
			break;
		}
		$id = $result['id'];
		$time = time();
		$talk_key = rand(1,99999999);
		$sql = "update monster_talk_".$monster." set talk='".$oo."',good=0,bad=0,time=".$time.",talk_key='".$talk_key."' where id=".$id;
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 2;
			break;
		}
		
		$userData->addTaskStat('comment');
		$userData->addEnergy(-1);
		$returnData->id = $id;
		$returnData->talk_key = $talk_key;
		$userData->write2DB();
	}
	while(false);
	
	
	
		
?> 