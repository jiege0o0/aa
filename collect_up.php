<?php 
	$id = $msg->id;
	function getLevelUpNeed($level){
		if($level == 1)
			return 30;
		if($level == 2)
			return 50;
		if($level == 3)
			return 80;
		return 120;
	}
	
	do{
		$num = $userData->getCollectNum($id);
		$level = $userData->collect->level->{$id};
		if(!$level)
		{
			require_once($filePath."cache/monster.php");
			$level = $monster_base[$id]['collect'];
		}
			
		if($level >= 4)//已满级了
		{
			$returnData->fail = 1;
			$returnData->sync_collect_level = new stdClass();
			$returnData->sync_collect_level->{$id} = $userData->collect->level->{$id};
			break;
		}
		$need = getLevelUpNeed($level + 1);
		if($num < $need)//数量不足
		{
			$returnData->fail = 2;
			$returnData->sync_collect_num = new stdClass();
			$returnData->sync_collect_num->{$id} = $num;
			break;
		}
		$userData->addCollect($id,-$need);
		$userData->collect->level->{$id} = $level + 1;
		
		$returnData->sync_collect_level = new stdClass();
		$returnData->sync_collect_level->{$id} = $userData->collect->level->{$id};
		$userData->write2DB();	

		$returnData->data = "OK";		
	}while(false);
	


?> 