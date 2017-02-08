<?php 
	$type=$msg->type;
	$pkdata = $userData->{$type}->pkdata;
	do{
		if(!$pkdata)
		{
			$returnData->fail = 1;
			$returnData->{"sync_".$type}->choose = null;
			break;
		}
		if($pkdata->version != $pk_version)
		{
			$returnData->fail = 2;
			$returnData->pk_version = $pk_version;
			break;
		}
		$team2Data = $pkdata->team2;	
		$team1Data = $pkdata->team1;
		$equalPK = $pkdata->isequal;
		$pkUserInfo = $pkdata->info;
		require_once($filePath."pk_action/pk.php");	
	}while(false);
	

?> 