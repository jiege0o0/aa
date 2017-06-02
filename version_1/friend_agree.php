<?php 
	//同意成为好友
	$logid = $msg->logid;
	$time = time();
	$maxFriend = 30;
	do{
		$sql = "select * from ".$sql_table."friend_log where id=".$logid." and to_gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//没这条记妹	
		{
			$returnData->fail = 1;
			break;
		}
		$otherid = $result['from_gameid'];
		
		
		$sql = "select * from ".$sql_table."user_friend where gameid in('".$gameid."','".$otherid."')";
		$arr = $conne->getRowsArray($sql);
		foreach($arr as $value)
		{
			if($value['friends'] && count(split(',',$value['friends'])) >= $maxFriend)
			{
				if($value['gameid'] == $gameid)//我的好友满了
				{
					$returnData->fail = 5;
				}
				else //对方好友满了
				{
					$returnData->fail = 6;
				}
				
				break;
			}
		}
		if($returnData->fail)
			break;
		
		

		$sql = "select nick,head,level,tec_force,award_force from ".$sql_table."user_data where gameid='".$otherid."'";
		$value = $conne->getRowsRst($sql);
		if(!$value)
		{
			$returnData->fail = 7;
			break;
		}
		$friendInfo = new stdClass();
		$friendInfo->nick = base64_encode($value['nick']);
		$friendInfo->head = $value['head'];
		$friendInfo->level = $value['level'];
		$friendInfo->force = $value['tec_force'] + $value['award_force'];
		$friendInfo->time = $time;
		
		if(!addFriend($userData->gameid,$otherid,$friendInfo))//把对方加入自己
		{
			$returnData->fail = 3;
			break;
		}
		
		$sql = "update ".$sql_table."friend_log set time=0 where id=".$logid;
		$result = $conne->uidRst($sql);
		if(!$result)//更新日志失败	
		{
			$returnData->fail = 2;
			break;
		}
		
		
		$friendInfo2 = new stdClass();
		$friendInfo2->nick = base64_encode($userData->nick);
		$friendInfo2->head = $userData->head;
		$friendInfo2->level = $userData->level;
		$friendInfo2->force = $userData->tec_force + $userData->award_force;
		$friendInfo2->time = $time;
		if(!addFriend($otherid,$userData->gameid,$friendInfo2))//把自己加入对方
		{
			$returnData->fail = 4;//加不了也没关系
		}
		
		$friendInfo->gameid = $otherid;
		$returnData->otherinfo = $friendInfo;
	}
	while(false);	
	
	//为gameid添加好友
	function addFriend($gameid,$addid,$otherInfo){
		global $conne,$sql_table;
		$sql = "select * from ".$sql_table."user_friend where gameid='".$gameid."'";
		$myFriendData = $conne->getRowsRst($sql);
		if($myFriendData)
		{
			if($myFriendData['friends'])
				$friends = split(',',$myFriendData['friends']);
			else
				$friends = array();
				
			if($myFriendData['friends_info'])
				$info = json_decode($myFriendData['friends_info']);
			if(!$info)
				$info = new stdClass();
			$info->{$addid} = $otherInfo; 	
				
			//写用户数据
			if(!in_array($addid,$friends,true))//没加过
			{
				array_push($friends,$addid);
				$sql = "update ".$sql_table."user_friend set friends='".join(',',$friends)."',friends_info='".json_encode($info)."' where gameid='".$gameid."'";
				return $conne->uidRst($sql);
			}
			return true;
		}
		else
		{
			$info = new stdClass();
			$info->{$addid} = $otherInfo; 	
			$sql = "insert into ".$sql_table."user_friend(gameid,friends,friends_info) values('".$gameid."','".$addid."','".json_encode($info)."')";
			return $conne->uidRst($sql);
		}
	}
	
		
?> 