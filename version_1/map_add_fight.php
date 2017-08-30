<?php 
	do{		
		if(!$userData->pk_common->map->add_fight_time || time() - $userData->pk_common->map->add_fight_time > 3600)
		{
			if(!$userData->pk_common->map->last_pk_data)
				break;
			$userData->pk_common->map->add_fight_time = time();
			
			

			
			
			$tableName = $sql_table."map_fight";
			$sql = "select id from ".$tableName." where gameid='".$userData->gameid."' and level=".$userData->pk_common->map->level." and time>0 limit 1";
			
			$result = $conne->getRowsRst($sql);
			if($result)//已有数据在
			{
				break;
			}

			$saveData = new stdClass();
			$saveData->head = $userData->head;
			$saveData->nick = base64_encode($userData->nick);
			
			// $sql = "select id from ".$tableName." where level=".$userData->pk_common->map->level." order by time asc limit 1";
			$sql = "update ".$tableName." set gameid='".$userData->gameid."',content='".json_encode($saveData)."',time=".time()." where level=".$userData->pk_common->map->level." order by time asc limit 1";
			$conne->uidRst($sql);
		}
	}while(false);
	


?> 