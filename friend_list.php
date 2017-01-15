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
			if(!$serverFriend[0])
				$serverFriend = array();
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
		//去掉不存在的好友的节点
		foreach($friendInfo as $key=>$value)
		{
			if(!in_array($key,$serverFriend,true))
				unset($friendInfo->{$key});
		}
		//添加新的好友节点
		foreach($serverFriend as $key=>$value)
		{
			if(!isset($friendInfo->{$value}))
			{
				$needRenew[] = $value;
				$callList[] = $value;
			}
		}
		//更新好友数据
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
			$sql = "select gameid,nick,head,level,tec_force,award_force from ".$sql_table."user_data where gameid in('".join($needRenew,"','")."')";
			$result2 = $conne->getRowsArray($sql);
			
			foreach($result2 as $key=>$value)
			{
				$otherid = $value['gameid'];
				$friendInfo->{$otherid} = new stdClass();
				$friendInfo->{$otherid}->nick = base64_encode($value['nick']);
				$friendInfo->{$otherid}->head = $value['head'];
				$friendInfo->{$otherid}->level = $value['level'];
				$friendInfo->{$otherid}->force = $value['tec_force'] + $value['award_force'];
				$friendInfo->{$otherid}->time = $time;
			}
			$sql = "update ".$sql_table."user_friend set friends_info='".json_encode($friendInfo)."' where gameid='".$userData->gameid."'";
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

		$len = count($callList);
		if($len > 0)
		{	
			for($i=0;$i<$len;$i++)
			{
				$callList[$i] = getFriendKey($callList[$i]);
			}
			$sql = "select friend_key,win1,win2,last_winner,last_time from ".$sql_table."friend_together where friend_key in(".join($callList,"','").") and last_time > ".$lastTime."";
			$conne->close_rst();
			$result = $conne->getRowsArray($sql);
			$friendpk = array();
			if($result)	
			{
				$len = count($result);
				for($i=0;$i<$len;$i++)
				{
					$key = getFriendByKey($result[$i]['friend_key']);
					$friendpk[$key] = $result[$i];
				}
			}
			$returnData->friendpk = $friendpk;
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