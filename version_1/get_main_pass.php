<?php 
	$level = $userData->main_game->hlevel + 1;
	$tableName = $sql_table.'main_pass';
	$sql = "select * from ".$tableName." where level=".$level;
	$sqlResult = $conne->getRowsArray($sql);
	$len = count($sqlResult);
	if($len > 0)
	{
		$level -= 5;
		if($level <= 0)
			$need = 0;
		else
			$need = ceil($level/10);
		$free = $userData->main_game->hlevel < 100 && $userData->main_game->fail &&
		$userData->main_game->fail >= ceil($userData->main_game->hlevel/5);
		if($need == 0)
			$free = true;
		do{
			if(!$free && !$userData->main_game->show_pass && $userData->getDiamond() < $need)//��ʯ����
			{
				$returnData->fail = 1;//��ʯ����
				$returnData->sync_diamond = $userData->diamond;
				break;
			}
			
			$userData->main_game->show_pass = true;
			$userData->setChangeKey('main_game');
			$returnData->list = $sqlResult;	
			if(!$free)
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