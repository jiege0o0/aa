<?php 
	//取好友巧遇
	require_once($filePath."pk_action/pk_tool.php");
	
	//随机排序
	function randomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
	
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
	
	
	
		$pkType = 'server_game';
		$pkLevel = getPKTableLevel($userData->server_game->exp,30);
		if(!testPKTable($pkType,$pkLevel))
		{
			$returnData->fail = 20;
			break;
		}
		$tableName = $sql_table.$pkType."_".$pkLevel;
		
		//到对应表中找
		$winKey = "";
		$sql = "select * from ".$tableName." where gameid!='".$userData->gameid."' and gameid!='0'";
		$result = $conne->getRowsArray($sql);
		$list = array();
		if($result)//还是没找到PK对象
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