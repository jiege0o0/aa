<?php 

	$tableName = $sql_table.'main_pass';
	$myKey = implode(",",$team1Data->list);
	$todayZero = todayZero();
	$dayLevel = $level + 5000;

	
	$sql = "select * from ".$tableName." where level=".$dayLevel;
	$sqlResult = $conne->getRowsArray($sql);
	$len = count($sqlResult);
	$haveSame = false;
	
	if($len > 0)
	{
		foreach($sqlResult as $key=>$value)
		{
			foreach($value as $key2=>$value2)
			{
				if($key2 == 'mkey' || $key2 == 'data')
					continue;
				$sqlResult[$key][$key2] = (int)($value2);
			}
		
			if($myKey == $value['mkey'] && isSameDate($value['time']))
			{
				$haveSame = true;
			}
		}
	}
	
	
	do{
		if($haveSame)
			break;
			
		$saveData = new stdClass();
		$saveData->pkdata = $team1Data;
		$saveData->gameid = $userData->gameid;
		$saveData->nick = base64_encode($userData->nick);
			
			
		if($len <10)
		{
			//插入数据
			$sql = "insert into ".$tableName."(level,data,score,mkey,pk_version,time) values(".$dayLevel.",'".json_encode($saveData).
			"',".'0'.",'".$myKey."',".$pk_version.",".time().")";
			$conne->uidRst($sql);
			break;
		}
		
		usort($sqlResult,sortMainPass);//时间小的在前	
		
		$replaceID = $sqlResult[0]['id'];
		$sql = "update ".$tableName." set time=".time().",data='".json_encode($saveData)."',score=".$fight.",mkey='".$myKey.
		"',pk_version=".$pk_version." where id=".$replaceID;
		
		$conne->uidRst($sql);	
		
	}while(false);

	
	function sortMainPass($a,$b){
		if($a['time'] < $b['time'])
			return -1;
		return 1;
	}
	

?> 