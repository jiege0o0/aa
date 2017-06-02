<?php 
	$file  = $dataFilePath.'day_game/server'.$serverID.'/game'.date('Ymd', time()).'.txt';
	do{
		if(!is_file($file))//文件未生成
		{
			$file2  = $dataFilePath.'log/server'.$serverID.'_create_2.txt';
			if(is_file($file2))//文件生成了,可以从表中找数据
			{
				require_once($filePath."tool/conn.php");
				$table = $sql_table.'server_game_equal';
				$begin = $msg->level + rand(-100,0);
				if($begin < 50)
					$begin = rand(51,90);
				$sql = "select game_data from ".$table." where id between ".$begin." and ".($begin + 30)." and last_time>0 limit 10";
				$result = $conne->getRowsArray($sql);
				if(count($result) != 10)//找不到纪录
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
				usort($result,dayGameRandomSortFun);
			}
			else
			{
				$result = array();
				require_once($filePath."random_fight_card.php");
				for($i=0;$i<10;$i++)
				{
					$oo = new stdClass();
					$oo->list = randomFightCard($msg->level);
					array_push($result,$oo);
				}
			}
			
			
			
			
			require_once($filePath."pk_action/get_pk_card.php");
			$choose = getPKCard($msg->user_level);
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
	
	function dayGameRandomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
?> 