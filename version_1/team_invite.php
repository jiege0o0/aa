<?php 
	//邀请加入战队
	$team = $msg->team;
	$teamName = $msg->team_name;
	$type = $msg->type;
	$hard = $msg->hard;
	$otherid = $msg->otherid;
	do{
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

		
		
		$othernick = $result['nick'];
		$oo = new stdClass();
		$oo->head = $userData->head;
		$oo->nick = base64_encode($userData->nick);
		$oo->team = $team;
		$oo->team_name = base64_encode($teamName);
		$oo->hard = $hard;
		$oo = json_encode($oo);
		
		$time = time();
		$sql = "update ".$sql_table."friend_log set time=".$time.",content='".$oo."' where from_gameid='".$userData->gameid."' and to_gameid='".$otherid."' and type = ".$type;
		if(!$conne->uidRst($sql))
		{
			$sql = "insert into ".$sql_table."friend_log(from_gameid,to_gameid,type,content,time) values('".$userData->gameid."','".$otherid."',".$type.",'".$oo."',".$time.")";
			if(!$conne->uidRst($sql))
			{
				$returnData->fail = 2;
				break;
			}
		}
		$returnData->data = 'ok';
	}
	while(false);
	
	
	
		
?> 