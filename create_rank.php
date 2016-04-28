<?php 
	//1战力榜，2等级榜，3过关榜，4server，5server_equal 
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
		file_put_contents($file1,'',LOCK_EX);
		file_put_contents($file2,'',LOCK_EX);
		file_put_contents($file3,'',LOCK_EX);
		file_put_contents($file4,'',LOCK_EX);
		file_put_contents($file5,'',LOCK_EX);
		
		$arr1 = array();
		$arr2 = array();
		$arr3 = array();
		$arr4 = array();
		$arr5 = array();
		
		$index = 1;
		$step = 100;
		$t = microtime(true);
		while(true)
		{
			$sql = "select * from ".$sql_table."user_data where uid>=".$index." and uid<".($index + $step)."";
			$result = $conne->getRowsArray($sql);
			if(!$result || count($result)==0)//已处理完
			{
				$returnData->data = 'ok';	
				break;
			}
			$len1 = count($arr1);
			$len2 = count($arr2);
			$len3 = count($arr3);
			$len4 = count($arr4);
			$len5 = count($arr5);
			
			foreach($result as $key=>$value)
			{
				$gu = new GameUser($value,true);
				//1战力榜，2等级榜，3过关榜，4server，5server_equal
				addToArr($arr1,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->tec_force + $gu->award_force,"value2"=>0));
				addToArr($arr2,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->level,"value2"=>$gu->exp));
				addToArr($arr3,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->main_game->level,"value2"=>-$gu->main_game->time));
				addToArr($arr4,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->server_game->exp,"value2"=>-$gu->server_game->time));
				addToArr($arr5,array("head"=>$gu->head,"gameid"=>$gu->gameid,"nick"=>$gu->nick,"value"=>$gu->server_game_equal->exp,"value2"=>-$gu->server_game_equal->time));
			}
			
			sortArr($arr1,$len1);
			sortArr($arr2,$len2);
			sortArr($arr3,$len3);
			sortArr($arr4,$len4);
			sortArr($arr5,$len5);
			$index += $step;
			
			$des = floor((microtime(true) - $t)*1000);
			if($des > 100)
			{
				usleep($des*1000);
				$t = microtime(true);
			}
			$conne->close_rst();
		}
		
		deleteValue2($arr1);
		deleteValue2($arr2);
		deleteValue2($arr3);
		deleteValue2($arr4);
		deleteValue2($arr5);
		
		file_put_contents($file1,json_encode($arr1),LOCK_EX);
		file_put_contents($file2,json_encode($arr2),LOCK_EX);
		file_put_contents($file3,json_encode($arr3),LOCK_EX);
		file_put_contents($file4,json_encode($arr4),LOCK_EX);
		file_put_contents($file5,json_encode($arr5),LOCK_EX);
			
		
	}while(false);
	
	//把合适的数据加到数组中
	function addToArr(&$arr,$data){
		if(count($arr)<100)
		{	
			array_unshift($arr,$data);
			return true;
		}
		else
		{
			if($data["value"] > $arr[99]["value"])
			{
				array_unshift($arr,$data);
				return true;
			}
		}
		return false;
	}
	
	//处理数级的数据（排序，长度控制）
	function sortArr(&$arr,$lastLen){
		$len = count($arr);
		if($len != $lastLen)
		{	
			usort($arr,sortFun);
			if($len > 100)
				array_splice($arr,100);
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