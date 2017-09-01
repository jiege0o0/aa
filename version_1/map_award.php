<?php 
	require_once($filePath."map_code.php");
	do{
		$b = resetMapData();
		if($userData->pk_common->map->bag <= 0)
		{
			$returnData->fail = 1;
			$returnData->data = $userData->pk_common->map;
			break;
		}
		$userData->pk_common->map->value += $userData->pk_common->map->bag;
		$userData->pk_common->map->bag = 0;
		
		if(!$returnData->fail)
			require_once($filePath."map_add_fight.php");
		
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = $userData->pk_common->map;
	}while(false);
	


?> 