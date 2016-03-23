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
		unset($userData->collect->num);
		$userData->collect->isred = $userData->collectIsRed();//初始红点
		
		
		$userData->honor = new stdClass();
		$userData->honor->isred = $userData->honorIsRed();
		
		

		if($userData->main_game->pkdata)
			$userData->main_game->pkdata = 1;
		if($userData->server_game->pkdata)
			$userData->server_game->pkdata = 1;
		if($userData->server_game_equal->pkdata)
			$userData->server_game_equal->pkdata = 1;
			
		if($userData->server_game->enemy && $userData->server_game->enemy->pkdata)
			unset($userData->server_game->enemy->pkdata);
			
		if($userData->server_game_equal->enemy && $userData->server_game_equal->enemy->pkdata)
			unset($userData->server_game_equal->enemy->pkdata);

		$returnData->data = $userData;
	}
	else//没这个玩家，要新增
	{
		$returnData-> fail = 2;
	}
?> 