<?php 
	$time = time()-24*3600;
	do{
		$sql = "select count(*) as num from ".$sql_table."pay_log where gameid='".$msg->gameid."' and orderno2='".$msg->order."' and time>".$time;
		$result = $conne->getRowsRst($sql);
		if($result['num'])//�ж�Ӧ����
		{
			$returnData->ok = true;
			break;
		}
		$returnData->fail = 1;
			break;
		
		
	}while(false);
?> 