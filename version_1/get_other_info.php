<?php 
	$otherid = $msg->otherid;
	$othernick = $msg->othernick;
	$returnData->stopLog = true;
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
		$returnUser->main_game = array('level'=>$otherUser->main_game->level,'award_force'=>$otherUser->main_game->award_force);
		$returnUser->day_game = array('score'=>$otherUser->day_game->score,'times'=>$otherUser->day_game->times);
		$returnUser->last_land = $otherUser->last_land;
		$returnUser->pk_common = $otherUser->pk_common;
		$returnUser->friends = $otherUser->friends;
		
		
		$returnData->info = $returnUser;
		
		if($msg->isfriend)
		{
			$sql = "select friend_key,win1,win2,last_winner,last_time from ".$sql_table."friend_together where friend_key=".infoGetFriendKey($otherUser->gameid)."";
			$result = $conne->getRowsRst($sql);
			$returnData->pk = $result;
		}

		
		
	}while(false);
	
	function infoGetFriendKey($value){
		global $msg; 
		if($value > $msg->mygameid)
			return "'".$value.$msg->mygameid."'";
		else 
			return "'".$msg->mygameid.$value."'";			
	}
?> 