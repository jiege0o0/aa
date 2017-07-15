<?php 
	//同意成为好友
	$logid = $msg->logid;
	$time = time();
	do{
		$sql = "select * from ".$sql_table."friend_log where id=".$logid." and to_gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//没这条记妹	
		{
			$returnData->fail = 1;
			break;
		}	
		if(!isSameDate($result['time']))//超时了
		{
			$returnData->fail = 1;
			break;
		}
		$otherid = $result['from_gameid'];
		$type = $result['type'];
		$content = json_decode($result['content']);
		$team = $content->team;
	
		
		
		$userData->resetTeamGame();

		$month =  (int)(date('m', $time));
		$day =  (int)(date('d', $time));
		
		if($type == 11)
		{
			if($userData->active->team_pve->team)
			{
				$returnData->fail = 6;
				break;
			}
			$todayTable = $sql_table."team_pve_".$month.'_'.$day;
			
		}
		
		
		$sql = "select * from ".$todayTable." where id= ".$team."";
		$teamResult = $conne->getRowsRst($sql);
		if(!$teamResult)//没这个队伍	
		{

			$returnData->fail = 2;
			break;
		}
		
		$player = new stdClass();
		$player->head = $userData->head;
		$player->nick = base64_encode($userData->nick);
		$player->gameid = $userData->gameid;
		$player->pk_time = 0;
		$player->buy_time = 0;
		$player->award = new stdClass();
		$player = json_encode($player);
		
		if(!$teamResult['player2'])
		{
			$sql = "update ".$todayTable." set player2='".$player."' where id=".$team;
			$teamResult['player2'] = $player;
		}
		else if(!$teamResult['player3'])
		{
			$sql = "update ".$todayTable." set player3='".$player."' where id=".$team;
			$teamResult['player3'] = $player;
		}
		else//人满了
		{
			$returnData->fail = 3;
			break;
		}
		
		$result = $conne->uidRst($sql);
		if(!$result)//添加队伍失败	
		{
			$returnData->fail = 4;
			break;
		}
		
		if($type == 11)
		{
			$userData->active->team_pve->lasttime = $time;
			$userData->active->team_pve->team = $teamResult['id'];
			$userData->setChangeKey('active');
		}

		userData->addTaskStat('friend_dungeon');
		$userData->write2DB();
		
		
		$sql = "update ".$sql_table."friend_log set time=0 where id=".$logid;
		$result = $conne->uidRst($sql);
		if(!$result)//更新日志失败	
		{
			$returnData->fail = 5;
			break;
		}

		$returnData->team = $teamResult;
	}
	while(false);	
		
?> 