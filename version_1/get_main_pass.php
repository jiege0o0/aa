<?php 
	$level = $userData->main_game->level + 1;
	$tableName = $sql_table.'main_pass';
	$sql = "select * from ".$tableName." where level=".$level;
	$sqlResult = $conne->getRowsArray($sql);
	$len = count($sqlResult);
	if($len > 0)
	{
		$need = ceil($level/10);
		$free = $userData->main_game->level < 100 && $userData->main_game->fail &&
		$userData->main_game->fail > 5 + floor($userData->main_game->level/100);
		do{
			if(!$free && !$userData->main_game->show_pass && $userData->getDiamond() < $need)//钻石不够
			{
				$returnData->fail = 1;//钻石不够
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
		$returnData->fail = -1;//没有数据，不扣
	}
	
	
	
	
	

?> 