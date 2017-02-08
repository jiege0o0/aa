<?php 
	do{
		if($msg->pk_version != $pk_version)
		{
			$returnData->fail = 2;
			$returnData->pk_version = $pk_version;
			break;
		}
		$team2Data = $msg->team2;	
		$team1Data = $msg->team1;
		$equalPK = $msg->isequal;	
		require_once($filePath."pk_action/pk.php");	
	}while(false);
?> 