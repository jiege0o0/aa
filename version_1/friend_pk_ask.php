<?php 
	//发出挑战请求
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");
	do{
		$otherid = $msg->otherid;
		$toNick = $msg->othernick;
		$toHead = $msg->otherhead;
		$isEqual = $msg->isequal;
		if($otherid > $userData->gameid)
		{
			$chooseKey = 'friend2_cards';
			$friendKey = $otherid.$userData->gameid;
		}
		else
		{
			$chooseKey = 'friend1_cards';
			$friendKey = $userData->gameid.$otherid;
		}
		$sql = "select ".$chooseKey." as choose from ".$sql_table."friend_together where friend_key = '".$friendKey."'";
		$result = $conne->getRowsRst($sql);
		

		if(!($result && $result['choose']))//无数据
		{
			$returnData->fail = 1;
			break;
			
		}
		
		if($userData->getFriendPKTimes() <= 0)//今日次数已完
		{
			$returnData->fail =4;
			$returnData->sync_friend = $userData->friends;
			break;
		}
		
		$fromList = json_decode($result['choose']);
		$myChoose = $msg->choose;
		
		
		$team1Data = changePKData($myChoose,'friend_gamse',$isEqual,$fromList);
		if($team1Data->fail)//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$contentOrg = new stdClass();
		$contentOrg->talk = base64_encode($msg->talk);
		$contentOrg->from_list = $fromList;
		$contentOrg->ask_choose = $team1Data;
		$contentOrg->isequal = $isEqual;
		$contentOrg->fromnick = base64_encode($userData->nick);
		$contentOrg->tonick = base64_encode($toNick);
		$contentOrg->fromhead = $userData->head;
		$contentOrg->tohead = $toHead;
		$content = json_encode($contentOrg);
		
		//写日志
		$time = time();
		$sql = "insert into ".$sql_table."friend_log(from_gameid,to_gameid,type,content,time) values('".$userData->gameid."','".$otherid."',2,'".$content."',".$time.")";
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 2;
			break;
		}
		$sql = "select last_insert_id() as id";
		$result = $conne->getRowsRst($sql);
		$data = new stdClass();
		$data->id = $result['id'];
		$data->from_gameid = $userData->gameid;
		$data->to_gameid = $otherid;
		$data->type = 2;
		$data->content = $contentOrg;
		$data->time = $time;
		
		//清数据
		$sql = "update ".$sql_table."friend_together set ".$chooseKey."='' where friend_key = '".$friendKey."'";
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 3;
			break;
		}
		
			
		$userData->addHistory($team1Data->list);
		$userData->addFriendPKTimes(-1);
		$userData->write2DB();
		
		$returnData->data = $data;
	}
	while(false);

?> 