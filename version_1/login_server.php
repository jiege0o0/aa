<?php
	require_once($filePath."tool/conn.php");
	require_once($filePath."object/game_user.php");
	$gameid = $serverID.'_'.$msg->id;
	$sql = "select * from ".$sql_table."user_data where gameid='".$gameid."'";
	$userData = $conne->getRowsRst($sql);
	if($userData)//有这个玩家
	{
		$time = time();
		$sql = "update ".$sql_table."user_data set last_land=".$time.",land_key='".$time."' where gameid='".$gameid."'";
		$conne->uidRst($sql);
		//$userData['last_land'] = $time;
		$userData['land_key'] = $time;
		$userData = new GameUser($userData);
		
		//简化用户数据
		// unset($userData->collect->num);
		// $userData->collect->isred = $userData->collectIsRed();//初始红点
		
		$honorRed = $userData->honorIsRed();
		$userData->honor = new stdClass();
		$userData->honor->isred = $honorRed;
		
		

		if($userData->main_game->pkdata)
			$userData->main_game->pkdata = array("version"=>$userData->main_game->pkdata->version,"time"=>$userData->main_game->pkdata->time);
		if($userData->server_game->pkdata)
			$userData->server_game->pkdata = array("version"=>$userData->server_game->pkdata->version,"time"=>$userData->server_game->pkdata->time);
		if($userData->server_game_equal->pkdata)
			$userData->server_game_equal->pkdata = array("version"=>$userData->server_game_equal->pkdata->version,"time"=>$userData->pkdata->pkdata->time);
		if($userData->day_game->pkdata)
			$userData->day_game->pkdata = array("version"=>$userData->day_game->pkdata->version,"time"=>$userData->day_game->pkdata->time);
			
		if($userData->server_game->enemy && $userData->server_game->enemy->pkdata && !$userData->server_game->pktime)
			unset($userData->server_game->enemy->pkdata);
			
		if($userData->server_game_equal->enemy && $userData->server_game_equal->enemy->pkdata && !$userData->server_game_equal->pktime)
			unset($userData->server_game_equal->enemy->pkdata);
			
		$sql = "select time from ".$sql_table."friend_log where (to_gameid='".$userData->gameid."' or (from_gameid='".$userData->gameid."' and (type=2 or type=3))) order by time desc limit 1";
		$result = $conne->getRowsRst($sql);
		if($result)	
		{
			$userData->friendtime = $result['time'];
		}
		
		if(!$userData->pk_common->my_card)
		{
			require_once($filePath."get_my_card.php");
		}
		
		
		
		$userData->pk_version = $pk_version;

		$returnData->data = $userData;
		
		$logtime = 1496631187;
		if($msg->logtime < $logtime)
		{
			$returnData->logtext = new stdClass();
			$returnData->logtext->text = '战斗动画调整，现在能通过动画方式浏览整个PK过程了|'.
			'把好友功能调整为3级开放|'.
			'首页布局调整，成就功能放到了玩家详情中去了|'.
			'加入一个团队PEV副本，需要玩家与好友组队共同挑战';
			$returnData->logtext->time = $logtime;
		}
		
	}
	else//没这个玩家，要新增
	{
		$returnData-> fail = 2;
	}
?> 