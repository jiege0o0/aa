<?php 
	$logid = $msg->logid;
	$time = time();
	$maxFriend = 30;
	do{
		$sql = "select * from ".$sql_table."map_fight_log where id=".$logid." and to_gameid='".$userData->gameid."'";
		$result = $conne->getRowsRst($sql);
		if(!$result)//û��������	
		{
			$returnData->fail = 11;
			break;
		}
		
		if($result['to_gameid'] != $userData->gameid)//���ܷ���
		{
			$returnData->fail = 12;
			break;
		}
		
		if($result['type'])//�ѷ���
		{
			$returnData->fail = 13;
			break;
		}

		require_once($filePath."map_fight_pk.php");
		if($returnData->fail)
			break;

		$sql = "update ".$sql_table."map_fight_log set type=1 where id=".$logid;
		$result = $conne->uidRst($sql);
		if(!$result)//������־ʧ��	
		{
			$returnData->fail = 20;
			break;
		}
	}
	while(false);	
	

?> 