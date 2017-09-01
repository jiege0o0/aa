<?php 
	$level = $msg->level;
	require_once($filePath."map_code.php");
	do{
		if($level < $userData->pk_common->map->max_level - 2)
		{
			$returnData -> fail = 1;
			break;
		}
		if($level > $userData->pk_common->map->max_level+1)
		{
			$returnData -> fail = 2;
			break;
		}
		$maxTime = ($userData->pk_common->map->level - 1) + 5;
		if($level == $userData->pk_common->map->max_level+1 && $userData->pk_common->map->step < $maxTime)
		{
			$returnData -> fail = 3;
			break;
		}
		
	
		resetMapData();
		if($userData->pk_common->map->bag > 0)
		{
			$userData->pk_common->map->value += $userData->pk_common->map->bag;
			$userData->pk_common->map->bag = 0;
		}
		
		$userData->pk_common->map->pk_value = 0;
		$userData->pk_common->map->level = $level;
		$userData->pk_common->map->enemy = null;
		if($level > $userData->pk_common->map->max_level)
		{	
			$userData->pk_common->map->max_level ++;
			$userData->pk_common->map->step = 0;
		}
		$userData->pk_common->map->lasttime = time();
		
		resetMapData();
		require_once($filePath."map_add_fight.php");
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = $userData->pk_common->map;
	}while(false);
	


?> 