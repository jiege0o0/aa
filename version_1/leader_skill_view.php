<?php 
	require_once($filePath."tool/conn.php");
	$tableName = $sql_table.'skill_total';
	$sql = "select * from ".$tableName." where num>0";
	$sqlResult = $conne->getRowsArray($sql);
	$returnData->list = $sqlResult;	
?> 