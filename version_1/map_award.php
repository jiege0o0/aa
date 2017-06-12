<?php 
	require_once($filePath."map_code.php");
	do{
		$b = $resetMapData();
		$userData->pk_common->map->value += $userData->pk_common->map->bag;
		$userData->pk_common->map->bag = 0;
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = $userData->pk_common->map;
	}while(false);
	


?> 