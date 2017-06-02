<?php 
	$time = time();
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	$todayTable = $sql_table."team_pve_".$month.'_'.$day;
	
	$time2 = $time - 3600*24;
	$month =  (int)(date('m', $time2));
	$day =  (int)(date('d', $time2));
	$yesterdayTable = $sql_table."team_pve_".$month.'_'.$day;

	$userData->resetTeamGame();
	
	$returnData->pve = new stdClass();
	if($userData->active->team_pve)
	{
		if($userData->active->team_pve->yteam)
		{	
			$sql = "select * from ".$yesterdayTable." where id=".$userData->active->team_pve->yteam;
			$returnData->pve->yesterday = $conne->getRowsRst($sql);
		}
		if($userData->active->team_pve->team)
		{	
			$sql = "select * from ".$todayTable." where id=".$userData->active->team_pve->team;
			$returnData->pve->today = $conne->getRowsRst($sql);
		}
	}
?> 