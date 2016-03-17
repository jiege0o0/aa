<?php 
	//取好友列表
	$lastTime = $msg->lasttime;
	$time = time();
	do{
		$sql = "select * from ".$sql_table."user_friend where gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)	
		{
			$serverFriend = array();
			$friendInfo = new stdClass();
		}
		else
		{
			$serverFriend = split(',',$result['friends']);
			$friendInfo = json_decode($result['friends_info']);
		}
		$returnData->serverfriends = $serverFriend;
		$callList = array();
		//更新好友数据（超过24小时的更新一次）
		$b = false;
		$needRenew = array();
		$renewTime = $time - 24*3600;
		// trace($result);
		// trace($friendInfo);
		foreach($friendInfo as $key=>$value)
		{
			if($value->time < $renewTime)
			{
				$needRenew[] = $key;
			}
			$callList[] = $key;
		}
		
		if(count($needRenew))
		{
			$sql = "select nick,head,level,tec_force,award_force from ".$sql_table."user_data where gameid in('".join($needRenew,"','")."')";
			$result2 = $conne->getRowsArray($sql);
			foreach($result2 as $key=>$value)
			{
				$friendInfo[$key] = new stdClass();
				$friendInfo[$key]->nick = $value['nick'];
				$friendInfo[$key]->head = $value['head'];
				$friendInfo[$key]->level = $value['level'];
				$friendInfo[$key]->force = $value['tec_force'] + $value['award_force'];
				$friendInfo[$key]->time = $time;
			}
			$sql = "update ".$sql_table."user_friend set friends_info='".json_encode($friendInfo)."' where gameid='".$gameid."'";
			$conne->uidRst($sql);
		}
		
		foreach($friendInfo as $key=>$value)
		{
			if($value->time >= $lastTime)
			{
				if(!$returnData->friendinfo)
					$returnData->friendinfo = new stdClass();
				$returnData->friendinfo->{$key} = $value;
			}
		}
		/////////////////////////////更新结束


		if(count($callList) > 0)
		{	
			$listStr = "friend_key in('".join($callList,"','")."') or ";
			$sql = "select friend_key,win1,win2,last_winner,last_time from ".$sql_table."friend_together where friend_key in('".join($callList,"','")."') and last_time > ".$lastTime."";
			$result = $conne->getRowsArray($sql);
			if(!$result)	
			{
				$result = array();
			}
			$returnData->friendpk = $result;
		}
		else
			$returnData->friendpk = array();
			
	}
	while(false);	
	
	function getFriendKey($value){
		global $userData; 
		if($value > $userData->gameid)
			return "'".$value.$userData->gameid."'";
		else 
			return "'".$userData->gameid.$value."'";			
	}
	
	function getFriendByKey($value){
		global $userData; 
		$value = str_replace($userData->gameid,'',$value);
		return  $value;		
	}
?> 