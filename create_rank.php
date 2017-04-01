<?php 
	//1战力榜，2等级榜，3过关榜，4,day,5server，6server_equal 
	$file1  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_1.txt';//今天的排行榜数据
	do{
		if(is_file($file1))//文件已生成,这个罗辑已被其它人触发了
		{
			$returnData->fail = 1;					
			break;
		}
		
		require_once($filePath."tool/conn.php");
		require_once($filePath."object/game_user.php");
		
		$file2  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_2.txt';
		$file3  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_3.txt';
		$file4  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_4.txt';
		$file5  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_5.txt';
		$file6  = $filePath.'day_rank/server'.$serverID.'/rank'.date('Ymd', time()).'_6.txt';
		file_put_contents($file1,'',LOCK_EX);
		file_put_contents($file2,'',LOCK_EX);
		file_put_contents($file3,'',LOCK_EX);
		file_put_contents($file4,'',LOCK_EX);
		file_put_contents($file5,'',LOCK_EX);
		file_put_contents($file6,'',LOCK_EX);
		
		$arr1 = array();
		$arr2 = array();
		$arr3 = array();
		$arr4 = array();
		$arr5 = array();
		$arr6 = array();
		
		$index = 1;
		$step = 100;
		$t = microtime(true);
		$time = time() - 3600*24*2;//2天前的数据不入榜
		$sql = "select gameid,nick,head,word,exp,level,tec_force,award_force,server_game,server_game_equal,main_game,day_game,last_land from ".$sql_table."user_data where last_land>=".$time."";
		$result = $conne->getRowsArray($sql);
		$len = count($result);
		$begin = 0;
			// if(!$result || count($result)==0)//已处理完
			// {
				// $returnData->data = 'ok';	
				// break;
			// }
			
		$des = floor((microtime(true) - $t)*1000);//取数据时间太长，停一下
		if($des > 100)
		{
			usleep($des*1000);
			$t = microtime(true);
		}
		
		
		foreach($result as $key=>$value)
		{
			$gu = new GameUser($value,true,true);
			//1战力榜，2等级榜，3过关榜，4day,4server，5server_equal
			addToArr($arr1,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->tec_force + $gu->award_force,"value2"=>0));
			addToArr($arr2,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->level,"value2"=>$gu->exp));
			if($gu->main_game->time)
				addToArr($arr3,array("head"=>$gu->head,"word"=>$gu->word,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->main_game->level,"value2"=>-$gu->main_game->time));
			if($gu->day_game->yscore)
				addToArr($arr4,array("head"=>$gu->head,"word"=>$gu->word,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->day_game->yscore,"value2"=>-$gu->day_game->ytime));
			if($gu->server_game->time)
				addToArr($arr5,array("head"=>$gu->head,"word"=>$gu->word,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->server_game->exp,"value2"=>-$gu->server_game->time));
			if($gu->server_game_equal->time)
				addToArr($arr6,array("head"=>$gu->head,"word"=>$gu->word,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->server_game_equal->exp,"value2"=>-$gu->server_game_equal->time));
			
			$begin ++;
			if($begin%100 == 0)//每处理100条，判断一下是否要休眠一下
			{
				$des = floor((microtime(true) - $t)*1000);
				if($des > 100)
				{
					usleep($des*1000);
					$t = microtime(true);
				}
			}
			
		}
			
			// sortArr($arr1,$len1);
			// sortArr($arr2,$len2);
			// sortArr($arr3,$len3);
			// sortArr($arr4,$len4);
			// sortArr($arr5,$len5);
			// $index += $step;
			
			
			
			
		$conne->close_rst();
		deleteValue2($arr1);
		deleteValue2($arr2);
		deleteValue2($arr3);
		deleteValue2($arr4);
		deleteValue2($arr5);
		deleteValue2($arr6);
		
		file_put_contents($file1,json_encode($arr1),LOCK_EX);
		file_put_contents($file2,json_encode($arr2),LOCK_EX);
		file_put_contents($file3,json_encode($arr3),LOCK_EX);
		file_put_contents($file4,json_encode($arr4),LOCK_EX);
		file_put_contents($file5,json_encode($arr5),LOCK_EX);
		file_put_contents($file6,json_encode($arr6),LOCK_EX);
			
		$returnData->ok = true;
	}while(false);
	
	//把合适的数据加到数组中
	function addToArr(&$arr,$data){
		$len = count($arr);
		if($len==0)
		{	
			array_unshift($arr,$data);
			return true;
		}
		else
		{	
			$lastData = $arr[$len-1];
			if($len>=100)
			{
				if($lastData["value"] > $data['value'])
					return false;
				if($lastData["value"] == $data['value'] && $lastData["value2"] >= $data['value2'])
					return false;
				array_pop($arr);
				$len --;
			}
			//向上插入到合适的位置
			for($index = $len - 1;$index>=0;$index--)
			{
				$lastData = $arr[$index];
				if(sortFun($lastData,$data) == -1)
				{
					$index++;
					break;
				}
			}
			
			if($index <=0)
				array_unshift($arr,$data);
			else if($index >= $len)
				array_push($arr,$data);
			else
				array_splice($arr,$index,0,array($data));
			return true;
		}
	}
	
	//排序的方法
	function sortFun($a,$b){
		if($a['value'] > $b['value'])
			return -1;
		if($a['value'] < $b['value'])
			return 1;
		if($a['value2'] > $b['value2'])
			return -1;
		if($a['value2'] < $b['value2'])
			return 1;
		if($a['gameid'] < $b['gameid'])
			return -1;
		return 1;	
	}
	
	//删除辅助排序字段
	function deleteValue2(&$arr){
		foreach($arr as $key=>$value)
		{
			unset($arr[$key]['value2']);
		}
		array_unshift($arr,array('time'=>time(),'info'=>true));
	}
?> 