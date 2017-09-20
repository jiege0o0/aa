<?php 
	//删除好友
	$otherid = $msg->otherid;
	do{
		if(!deleteFriend($userData->gameid,$otherid))
		{
			$returnData->fail = 1;
			break;
		}
		
		if(!deleteFriend($otherid,$userData->gameid))
		{
			$returnData->fail = 2;
			break;
		}
		
		$returnData->data = 'ok';
	}
	while(false);
	
		
	//删除好友
	function deleteFriend($gameid,$addid){
		global $conne,$sql_table;
		$sql = "select * from ".$sql_table."user_friend where gameid='".$gameid."'";
		$myFriendData = $conne->getRowsRst($sql);
		if($myFriendData)
		{
			if($myFriendData['friends'])
				$friends = split(',',$myFriendData['friends']);
			else
				return true;
	
			//写用户数据
			if(in_array($addid,$friends,true))//加过
			{
				if($myFriendData['friends_info'])
					$info = json_decode($myFriendData['friends_info']);
				if(!$info)
					$info = new stdClass();
				unset($info->{$addid}); 	
			
				$index = array_search($addid,$friends);//这里取数组下标
				array_splice($friends,$index,1);
				$sql = "update ".$sql_table."user_friend set friends='".join(',',$friends)."',friends_info='".json_encode($info)."' where gameid='".$gameid."'";
				return $conne->uidRst($sql);
			}
			return true;
		}
		return true;
	}
	
	
	
		
?> 