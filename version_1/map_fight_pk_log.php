<?php 
	//取列表
	$lastLogid = $msg->logid;
	do{
		$sql = "select * from ".$sql_table."map_fight_log where (to_gameid='".$userData->gameid."' or from_gameid='".$userData->gameid."') and id>".$lastLogid." and time>".(time() - 3600*24*3);
		$result = $conne->getRowsArray($sql);
		if(!$result)	
		{
			$returnData->list = array();
			break;
		}
		$returnData->list = $result;
	}
	while(false);	
?> 