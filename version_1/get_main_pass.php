<?php 
	$level = $userData->main_game->level + 1;
	$tableName = $sql_table.'main_pass';
	$sql = "select * from ".$tableName." where level=".$level;
	$sqlResult = $conne->getRowsArray($sql);
	$len = count($sqlResult);
	if($len > 0)
	{
		$need = ceil($level/20);
		do{
			if(!$userData->main_game->show_pass && $userData->getDiamond() < $need)//��ʯ����
			{
				$returnData->fail = 1;//��ʯ����
				$returnData->sync_diamond = $userData->diamond;
				break;
			}
			
			$userData->main_game->show_pass = true;
			$userData->setChangeKey('main_game');
			$returnData->list = $sqlResult;	
			$userData->addDiamond(-$need);
			$userData->write2DB();	
			$returnData->data = 'ok';
		}while(false);
	}
	else
	{
		$returnData->fail = -1;//û�����ݣ�����
	}
	
	
	
	
	

?> 