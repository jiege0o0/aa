<?php 
	$skillID = $msg->skillid;
	$tableName = $sql_table.'skill_log';
	$sql = "select * from ".$tableName." where skillid=".$skillID;
	$sqlResult = $conne->getRowsArray($sql);
	$returnData->list = $sqlResult;	

?> 