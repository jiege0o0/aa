<?php 
	//接受挑战请求
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");
	do{
		$logid = $msg->logid;
		$myChoose = $msg->choose;
		$sql = "select * from ".$sql_table."friend_log where id=".$logid." and to_gameid='".$userData->gameid."'";
		$logResult = $conne->getRowsRst($sql);
		if(!$logResult)//无数据
		{
			
			$returnData->fail = 1;
			break;
		}
		if($logResult['type'] !=2)//不是PK类型
		{
			$returnData->fail = 2;
			break;
		}
		

		$content = json_decode($logResult['content']);
		$fromList = $content->from_list;
		$askChoose = $content->ask_choose;
		$isEqual = $content->isequal;
		if(!$fromList || !$askChoose)//数据格式不对
		{
			$returnData->fail = 3;
			break;
		}
		
		if($content->answer_choose)//已挑战过了
		{
			$returnData->fail = 4;
			break;
		}
		
		$team2Data = $askChoose;
		
		$team1Data = changePKData($myChoose,'friend_gamse',$isEqual,$fromList);
		if($team1Data->fail)//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$equalPK = $isEqual;
		$pkUserInfo = new stdClass();
		$pkUserInfo->fromnick = $content->fromnick;
		$pkUserInfo->fromhead = $content->fromhead;
		$pkUserInfo->fromgameid = $logResult['from_gameid'];
		$pkUserInfo->tonick = $content->tonick;
		$pkUserInfo->tohead = $content->tohead;
		$pkUserInfo->togameid = $logResult['to_gameid'];

		
		require_once($filePath."pk_action/pk.php");
		
		$content->answer_choose = $team1Data;
		$content->result = $result;
		
		$content = json_encode($content);
		
		
		//写日志
		$time = time();
		$sql = "update ".$sql_table."friend_log set content='".$content."',time=".$time." where id=".$logid;
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 5;
			break;
		}
		
		//更新数据
		$otherid = $logResult['from_gameid'];
		if($otherid > $userData->gameid)
		{
			$friendKey = $otherid.$userData->gameid;
			if($result == 1)
			{
				$sql = "update ".$sql_table."friend_together set win2=win2+1,last_winner='".$userData->gameid."',last_time=".$time." where friend_key = '".$friendKey."'";
			}
			else
			{
				$sql = "update ".$sql_table."friend_together set win1=win1+1,last_winner='".$otherid."',last_time=".$time." where friend_key = '".$friendKey."'";
			}			
		}
		else
		{
			$friendKey = $userData->gameid.$otherid;
			if($result == 1)
			{
				$sql = "update ".$sql_table."friend_together set win1=win1+1,last_winner='".$userData->gameid."',last_time=".$time." where friend_key = '".$friendKey."'";
			}
			else
			{
				$sql = "update ".$sql_table."friend_together set win2=win2+1,last_winner='".$otherid."',last_time=".$time." where friend_key = '".$friendKey."'";
			}		
		}
		
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 6;
			break;
		}
		
		$userData->addHistory($team1Data->list);
		$userData->write2DB();
		
	}
	while(false);

?> 