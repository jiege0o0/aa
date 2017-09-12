<?php 
	require_once($filePath."tool/conn.php");
	$tableName = $sql_table.'skill_log';
	$sql = "select * from ".$tableName." order by `time` limit 30";
	$sqlResult = $conne->getRowsArray($sql);
	$returnData->list = $sqlResult;		
?> 