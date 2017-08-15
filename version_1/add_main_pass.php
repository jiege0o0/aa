<?php 

	$tableName = $sql_table.'main_pass';
	$myKey = implode(",",$team1Data->list);
	$fight = $team1Data->fight;
	
	
	$sql = "select * from ".$tableName." where level=".$level;
	$sqlResult = $conne->getRowsArray($sql);
	$len = count($sqlResult);
	$sameMin = null;
	$sameMax = null;
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
			
			if($myKey == $value['mkey'])
			{
				if(!$sameMax)
				{
					$sameMax = $value;
					$sameMin = $value;
				}
				else{
					if($sameMax['score'] < $value['score'])
						$sameMax = $value;
					else if($sameMin['score'] > $value['score'])
						$sameMin = $value;
				}
			}
		}
	}
	
	$saveData = new stdClass();
	$saveData->pkdata = $team1Data;
	$saveData->gameid = $userData->gameid;
	$saveData->nick = base64_encode($userData->nick);
	do{
		if($len <10)
		{
			if($len >= 5)
			{
			
				if($sameMin && $sameMin['score'] <= $fight)
					break;
			}
			//��������
			$sql = "insert into ".$tableName."(level,data,score,mkey,pk_version,time) values(".$level.",'".json_encode($saveData).
			"',".$fight.",'".$myKey."',".$pk_version.",".time().")";
			$conne->uidRst($sql);
			break;
		}
		
		if($sameMax && $sameMax['score'] <= $fight && time() - $sameMax['time'] < 3600*24*7)//��ͬ���ֲ���������
			break;
			
		
		//ѡ��һ���������滻
		// usort($sqlResult,sortMainPass1);//ս����͵Ĳ��ᱻ�ỻ
		// array_shift($sqlResult);
		
		usort($sqlResult,sortMainPass2);//ս����ǰ2�͵Ĳ���PK�汾Ӱ��
		array_shift($sqlResult);
		array_shift($sqlResult);
		// array_shift($sqlResult);
		
		usort($sqlResult,sortMainPass3);//�ɰ汾��߷ֵ�
		$replaceID = $sqlResult[0]['id'];
		

		
		$sql = "update ".$tableName." set time=".time().",data='".json_encode($saveData)."',score=".$fight.",mkey='".$myKey.
		"',pk_version=".$pk_version." where id=".$replaceID;
		
		$conne->uidRst($sql);	
	}while(false);
	
	
	// function sortMainPass1($a,$b){
		// if($a['score'] < $b['score'])
			// return -1;
		// if($a['score'] > $b['score'])
			// return 1;
		// if($a['pk_version'] > $b['pk_version'])
			// return -1;
		// if($a['pk_version'] < $b['pk_version'])
			// return 1;
		// if($a['time'] < $b['time'])
			// return -1;
		// return 1;
	// }
	
	function sortMainPass2($a,$b){
		if($a['score'] < $b['score'])
			return -1;
		if($a['score'] > $b['score'])
			return 1;
		if($a['pk_version'] > $b['pk_version'])
			return -1;
		if($a['pk_version'] < $b['pk_version'])
			return 1;
		if($a['time'] > $b['time'])
			return -1;
		return 1;
	}
	
	function sortMainPass3($a,$b){
		if($a['pk_version'] < $b['pk_version'])
			return -1;
		if($a['pk_version'] > $b['pk_version'])
			return 1;
		if($a['time'] < $b['time'])
			return -1;		
		if($a['time'] > $b['time'])
			return 1;
		if($a['score'] > $b['score'])
			return -1;
		
		return 1;
	}

?> 