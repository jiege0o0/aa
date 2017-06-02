<?php 
	//取好友巧遇
	require_once($filePath."pk_action/pk_tool.php");
	
	
	do{
		$sql = "select * from ".$sql_table."user_friend where gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)	
		{
			$serverFriend = array();
		}
		else
		{
			$serverFriend = split(',',$result['friends']);
			if(!$serverFriend[0])
				$serverFriend = array();
		}
		
		$pkType='server_game';
		$tableName = $sql_table.$pkType;
		$index = $userData->{$pkType}->exp + 50;
		$begin = $index - 20;
		if($begin < 1)
			$begin = 1;
		$end = $index + 20;
		if($end < 10)
			$end = 30;
		
		//到对应表中找
		$sql = "select * from ".$tableName." where id between ".$begin." and ".$end." and last_time>0 and gameid!='".$userData->gameid."'";
		$result = $conne->getRowsArray($sql);

		$list = array();
		if($result)
		{
			foreach($result as $key=>$value)
			{
				if(in_array($value['gameid'],$serverFriend,true))
					continue;
				$team2Data = json_decode($value['game_data']); 
				$info = $team2Data->userinfo;
				array_push($list,array("openid"=>$value['gameid'],
				'nick'=>$info->nick,
				'head'=>$info->head,
				'level'=>$info->level,
				'force'=>$info->force
				));
				array_push($serverFriend,$value['gameid']);
			} 
			
			if(count($list) > 5)
			{
				usort($list,randomSortFun);
				$list = array_slice($list,0,5);
			}
		}
		$returnData->list = $list;
			
	}
	while(false);	
	
?> 