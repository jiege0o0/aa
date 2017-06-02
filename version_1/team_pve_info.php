<?php 
	$userData->resetTeamGame();
	$yesterday = $msg->yesterday;
	
	$time = time();
	if($yesterday)
	{
		$time -= 3600*24;
	}
		
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	$todayTable = $sql_table."team_pve_".$month.'_'.$day;

	if($yesterday)
	{
		$sql = "select * from ".$todayTable." where id=".$userData->active->team_pve->yteam;
		$returnData->yesterday = $conne->getRowsRst($sql);
	}
	else if($userData->active->team_pve && $userData->active->team_pve->team)
	{
		$sql = "select * from ".$todayTable." where id=".$userData->active->team_pve->team;
		$returnData->pve = $conne->getRowsRst($sql);
	}
?> 