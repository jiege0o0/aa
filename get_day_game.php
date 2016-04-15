<?php 
	$file  = $filePath.'day_game/game'.date('Ymd', time()).'.txt';
	do{
		if(!is_file($file))//文件未生成
		{
			require_once($filePath."tool/conn.php");
			$table = $sql_table.'server_game_equal_';
			$beginIndex = 5;
			$sql = "SHOW TABLES LIKE '".$table.$beginIndex."'";
			while(!$conne->getRowsNum($sql))//没这个表
			{
				$beginIndex -- ;
				$sql = "SHOW TABLES LIKE '".$table.$beginIndex."'";
				if($beginIndex <= 0)
				{	
					break;
				}
			}
			if($beginIndex <= 0)//找不到表
			{
				$returnData->fail = 1;					
				break;
			}
			
			$sql = "select game_data from ".$table.$beginIndex." order by result desc, last_time asc limit 10";
			$result = $conne->getRowsArray($sql);
			if(count($result)!=10)//找不到纪录
			{	
				$returnData->fail = 2;
				break;
			}
			$len = count($result);
			for($i=0;$i<$len;$i++)
			{
				$temp = $result[$i]['game_data'];
				$temp = json_decode($temp);
				$result[$i] = $temp->pkdata;
			}
			
			require_once($filePath."pk_action/get_pk_card.php");
			$choose = getPKCard(50);
			$content = new stdClass();
			$content->choose = $choose;
			$content->levels = $result;
			$content = json_encode($content);
			
			if(!is_file($file))//文件未生成
			{
				if(!file_put_contents($file,$content,LOCK_EX))//无法写入
				{
					$returnData->fail = 3;
					break;
				}
			}
			else
			{
				$content = null;
			}
		}
		
		if(!$returnData->fail)
		{
			if(!$content)
				$content = file_get_contents($file);
			$returnData->content = $content;
		}
	}while(false);
?> 