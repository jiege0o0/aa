<?php 

	$id = $msg->headid;
	do{

		if($userData->head == $id)
		{
			$returnData->fail = 1;//头像不足
			break;
		}
		
		if($userData->getDiamond() < 100)
		{
			$returnData->fail = 2;//钻石不够
			$returnData->sync_diamond = $userData->diamond;
			break;
		}
		
		$userData->addDiamond(-100);
		$userData->head = $id;
		$userData->setChangeKey('head');
		$userData->write2DB();	
		
		//更新技能头像
		$oo = new stdClass();
		$oo->head = $userData->head;
		$oo->nick = base64_encode($userData->nick);
		$oo = json_encode($oo);
		$sql = "update into ".$sql_table."skill_log set content=".$oo." where gameid='".$userData->gameid."'";
		$conne->uidRst($sql);
		
		
		$sql = "update ".$sql_table."skill_total set num=num+1 where id=".$skillID;

		$returnData->data = "OK";		
	}while(false);
	


?> 