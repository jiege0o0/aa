<?php 
	$otherid = $msg->otherid;
	$othernick = $msg->othernick;
	do{
		require_once($filePath."tool/conn.php");
		require_once($filePath."object/game_user.php");
		$sql = "select * from ".$sql_table."user_data where gameid='".$otherid."' or nick='".$othernick."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)
		{
			$returnData->fail = 1;
			break;
		}
		$otherUser =  new GameUser($result,true);
		$returnUser = new stdClass();
		$returnUser->gameid = $otherUser->gameid;
		$returnUser->nick = $otherUser->nick;
		$returnUser->level = $otherUser->level;
		$returnUser->force = $otherUser->tec_force + award_force;
		$returnUser->server_game = array('exp'=>$otherUser->server_game->exp,'win'=>$otherUser->server_game->win,'total'=>$otherUser->server_game->total);
		$returnUser->server_game_equal = array('exp'=>$otherUser->server_game_equal->exp,'win'=>$otherUser->server_game_equal->win,'total'=>$otherUser->server_game_equal->total,'max'=>$otherUser->server_game_equal->max);
		$returnUser->main_game = array('level'=>$otherUser->main_game->level);
		
		
		$returnData->info = $returnUser;

		
		
	}while(false);
?> 