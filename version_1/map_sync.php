<?php 
	require_once($filePath."map_code.php");
	do{
		if(!$userData->pk_common->map->lasttime)
		{
			require_once($filePath."map_start.php");
			return;
		}
		$b = resetMapData();
		if($b)
		{
			$userData->setChangeKey('pk_common');
			$userData->write2DB();	
		}
		$returnData->data = $userData->pk_common->map;
	}while(false);
	


?> 