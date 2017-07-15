<?php 
	//申请成为好友
	$maxFriend = 30;
	$otherid = $msg->otherid;
	do{
		if($otherid == 'npc')
		{
			$userData->addTaskStat('friend');
			$userData->write2DB();
			$returnData->data = 'ok';
			break;
		}
		if($otherid == $userData->gameid)//不能向自己请求
		{
			$returnData->fail = 3;
			break;
		}
		$sql = "select nick,friends from ".$sql_table."user_data where gameid='".$otherid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//对方不存在	
		{
			$returnData->fail = 1;
			break;
		}
		
		$friends = $result['friends'];
		if(!$friends)
			$friends = '{}';
		$friends = json_decode($friends);
		if($friends->stop)
		{
			$returnData->fail = 6;
			break;
		}
		
		$sql = "select * from ".$sql_table."user_friend where gameid in('".$gameid."','".$otherid."')";
		$arr = $conne->getRowsArray($sql);
		foreach($arr as $value)
		{
			if($value['friends'] && count(split(',',$value['friends'])) >= $maxFriend)
			{
				if($value['gameid'] == $gameid)//我的好友满了
				{
					$returnData->fail = 4;
				}
				else //对方好友满了
				{
					$returnData->fail = 5;
				}
				
				break;
			}
		}
		if($returnData->fail)
			break;
		
		
		$othernick = $result['nick'];
		$oo = new stdClass();
		$oo->head = $userData->head;
		$oo->nick = base64_encode($userData->nick);
		$oo->level = $userData->level;
		$oo->force = $userData->tec_force + $userData->award_force;
		$oo = json_encode($oo);
		
		$time = time();
		$sql = "update ".$sql_table."friend_log set time=".$time.",content='".$oo."' where from_gameid='".$userData->gameid."' and to_gameid='".$otherid."' and type=1";
		if(!$conne->uidRst($sql))
		{
			$sql = "insert into ".$sql_table."friend_log(from_gameid,to_gameid,type,content,time) values('".$userData->gameid."','".$otherid."',1,'".$oo."',".$time.")";
			if(!$conne->uidRst($sql))
			{
				$returnData->fail = 2;
				break;
			}
		}
		
		$userData->addTaskStat('friend');
		$userData->write2DB();
		$returnData->data = 'ok';
	}
	while(false);
	
	
	
		
?> 