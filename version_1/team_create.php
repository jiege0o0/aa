<?php 
	$name = $msg->name;
	$hard = $msg->hard;
	$type = 'team_'.$msg->type;
	
	$userData->resetTeamGame();
	do{
		if($userData->active->{$type} && $userData->active->{$type}->team)//已有队伍
		{
			$returnData->fail = 1;
				break;
		}
		
		$time = time();
		$month =  (int)(date('m', $time));
		$day =  (int)(date('d', $time));
		$todayTable = $sql_table.$type."_".$month.'_'.$day;
		
		
		$sql = "select nick from ".$todayTable." where nick='".$name."'";
		$result = $conne->getRowsRst($sql);
		if($result)//已有队伍注册过
		{
			$returnData -> fail = 2;
			break;
		}

		
		
		
		
		$player1 = new stdClass();
		$player1->head = $userData->head;
		$player1->nick = base64_encode($userData->nick);
		$player1->gameid = $userData->gameid;
		$player1->pk_time = 0;
		$player1->buy_time = 0;
		$player1->award = new stdClass();
		$player1 = json_encode($player1);
		
		
		$game_data = new stdClass();
		$game_data->hard = $hard;
		$game_data->finish = new stdClass();
		$game_data = json_encode($game_data);
		
		$time = time();
		$sql = "insert into ".$todayTable."(nick,player1,game_data,time) values('".$name."','".$player1."','".$game_data."',".$time.")";
		$num = $conne->uidRst($sql);
		if($num == 1){//注册成功
			$sql = "select last_insert_id() as id";
			$returnData ->data = $conne->getRowsRst($sql);
			
			$userData->addTaskStat('friend_dungeon');
			$userData->active->{$type}->lasttime = $time;
			$userData->active->{$type}->team = $returnData->data['id'];
			$userData->setChangeKey('active');
			$userData->write2DB();
		}
		else
		{
			$returnData -> fail = 3;
			debug($sql);
		}
		
	}while(false);
	

?> 