<?php 
	$rankType = $msg->rank_type;
	$file  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time())."_".$rankType.'.txt';//今天的排行榜数据
	
	
	do{
		if(!is_file($file))//文件未生成
		{
			$returnData->fail = 1;
			$returnData->yestodayRank = getYestodayRank();
			break;
		}
		$content = file_get_contents($file);
		if(!content)//文件已生成，但内容为空
		{
			$returnData->fail = 2;
			$returnData->yestodayRank = getYestodayRank();
			break;
		}
		$returnData->rank = $content;
	}while(false);
	
	function getYestodayRank(){
		global $serverID;
		$index = 1;
		$file2  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time() - 3600*24*$index)."_".$rankType.'.txt';//昨天的排行榜数据
		while(!is_file($file2))//文件未生成,一直向前找
		{
			$index ++;
			if($index == 8)//7天找不到就不找了
				return '';
			$file2  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time() - 3600*24*$index)."_".$rankType.'.txt';//昨天的排行榜数据
			
		}
		return file_get_contents($file2);
	}
?> 