<?php 
	require_once($filePath."tool/conn.php");
	$teamid = $msg->teamid;
	
	$time = time();		
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	$todayTable = $sql_table."team_pve_".$month.'_'.$day;

	$sql = "select * from ".$todayTable." where id=".$teamid;
	$returnData->data = $conne->getRowsRst($sql);
?> 