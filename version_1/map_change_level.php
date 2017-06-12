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
			$returnData -> fail = 1;
			break;
		}
		if($level == $userData->pk_common->map->max_level+1 && $userData->pk_common->map->step < 10)
		{
			$returnData -> fail = 1;
			break;
		}
		
	
		$resetMapData();
		$userData->pk_common->map->value += $userData->pk_common->map->bag;
		$userData->pk_common->map->bag = 0;
		$userData->pk_common->map->pk_value = 0;
		$userData->pk_common->map->level = $level;
		if($level > $userData->pk_common->map->max_level)
		{	
			$userData->pk_common->map->max_level ++;
			$userData->pk_common->map->step = 0;
		}
		$userData->pk_common->map->lasttime = time();
		
		$resetMapData();
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = $userData->pk_common->map;
	}while(false);
	


?> 