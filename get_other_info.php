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
		$returnUser->head = $otherUser->head;
		$returnUser->word = $otherUser->word;
		$returnUser->level = $otherUser->level;
		$returnUser->force = $otherUser->tec_force + $otherUser->award_force;
		$returnUser->server_game = array('exp'=>$otherUser->server_game->exp,'win'=>$otherUser->server_game->win,'total'=>$otherUser->server_game->total,'top'=>$otherUser->server_game->top);
		$returnUser->server_game_equal = array('exp'=>$otherUser->server_game_equal->exp,'win'=>$otherUser->server_game_equal->win,'total'=>$otherUser->server_game_equal->total,'max'=>$otherUser->server_game_equal->max,'top'=>$otherUser->server_game_equal->top);
		$returnUser->main_game = array('level'=>$otherUser->main_game->level);
		$returnUser->day_game = array('score'=>$otherUser->day_game->score,'times'=>$otherUser->day_game->times);
		$returnUser->last_land = $otherUser->last_land;
		$returnUser->pk_common = $otherUser->pk_common;
		
		
		$returnData->info = $returnUser;

		
		
	}while(false);
?> 