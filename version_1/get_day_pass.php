<?php 
	$level = $userData->day_game->level + 1;
	$tableName = $sql_table.'main_pass';
	$sql = "select * from ".$tableName." where level=".($level + 5000);
	$sqlResult = $conne->getRowsArray($sql);
	
	$returnArr = array();
	foreach($sqlResult as $key=>$value)
	{
		if(isSameDate($value['time']))
		{
			array_push($returnArr,$value);
		}
	}
	$len = count($returnArr);
	if($len > 0)
	{
		$need = ceil($level*10);
		do{
			if(!$userData->main_game->show_pass && $userData->getDiamond() < $need)//��ʯ����
			{
				$returnData->fail = 1;//��ʯ����
				$returnData->sync_diamond = $userData->diamond;
				break;
			}
			
			$userData->day_game->show_pass = true;
			$userData->setChangeKey('day_game');
			$returnData->list = $returnArr;	
			$userData->addDiamond(-$need);
			$userData->write2DB();	
			$returnData->data = 'ok';
		}while(false);
	}
	else
	{
		$returnData->stopLog = true;
		$returnData->fail = -1;//û�����ݣ�����
	}
	
	
	
	
	

?> 