<?php 
	//申请成为好友
	$talk = $msg->talk;
	$otherid = $msg->otherid;
	do{
		if($otherid == $userData->gameid)//不能向自己请求
		{
			$returnData->fail = 3;
			break;
		}
		if($userData->getFriendTalk() <=0)//今天不能再发了
		{
			$returnData->fail = 4;
			$returnData->sync_friends = $userData->friends;
			break;
		}
		$sql = "select nick from ".$sql_table."user_data where gameid='".$otherid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//对方不存在	
		{
			$returnData->fail = 1;
			break;
		}
		
		$othernick = $result['nick'];
		$oo = new stdClass();
		$oo->head = $userData->head;
		$oo->nick = $userData->nick;
		$oo->talk = $talk;
		$oo = json_encode($oo);
		
		$time = time();
		$sql = "insert into friend_log(from_gameid,to_gameid,type,content,time) values('".$userData->gameid."','".$otherid."',3,'".$oo."',".$time.")";
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 2;
			break;
		}
		
		$userData->addFriendTalk(-1);
		$returnData->data = 'ok';
	}
	while(false);
	
	
	
		
?> 