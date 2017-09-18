<?php 
	$logid = $msg->logid;
	$type = $msg->type;
	do{
		if($type == 1)//分身
		{
			if($userData->getDiamond() < 100)//钻石不够
			{
				$returnData->fail = 1;//钻石不够
				$returnData->sync_diamond = $userData->diamond;
				break;
			}
			$userData->addDiamond(-100);
		}
		else //复制
		{
			$propNum = $userData->getPropNum(22);
			if($propNum < 1)
			{
				$returnData->fail = 2;
				$returnData->sync_prop = new stdClass();
				$returnData->sync_prop->{'22'} = $propNum;
				break;
			}
			$userData->addProp(22,-1);
		}
	
	
		$tableName = $sql_table.'skill_log';
		$sql = "select * from ".$tableName." where id=".$logid;
		$skillResult = $conne->getRowsRst($sql);
		if(!$skillResult)
		{
			$returnData->fail = 3;
			break;
		}
		
		if(!$userData->tec->copy_skill)
			$userData->tec->copy_skill = new stdClass();
		
		$time = time();
		$copyTime = (int)$skillResult['copy_time'];//结束时间
		if($type == 1)//分身
		{
			if($copyTime - $time > 10*60)
			{
				$returnData->fail = 4;
				$returnData->copytime = $copyTime;
				break;
			}
			$endTime = $time + 24*3600;
			$copyTime = $time + 3600;
		}
		else //复制
		{
			if($copyTime > $time)
			{
				$returnData->fail = 4;
				$returnData->copytime = $copyTime;
				break;
			}
			$endTime = $time + 3600;
			$copyTime = $time + 600;
		}
		
		//加技能
		$userData->tec->copy_skill->{$skillResult['skillid']} = $endTime;
		
		//清过期技能
		$deleteArr = array();
		foreach($userData->tec->copy_skill as $key=>$value)
		{
			if($value < $time)
				array_push($deleteArr,$key);
		}
		foreach($deleteArr as $key=>$value)
		{
			unset($userData->tec->copy_skill->{$value});
		}
		
		$userData->setChangeKey('tec');
		
		//更新技能日志
		$sql = "update ".$tableName." set copy_time=".$copyTime." where id=".$logid;
		$conne->uidRst($sql);
		
		//更新对方数据
		$sql = "select public_value from ".$sql_table."user_data where gameid='".$skillResult['gameid']."'";
		$otherResult = $conne->getRowsRst($sql);
		if($otherResult)
		{	
			if($otherResult['public_value'])
				$public_value = json_decode($otherResult['public_value']);
			else 
				$public_value = new stdClass();
			if(!$public_value->skill)	
			{
				$public_value->skill = new stdClass();
				$public_value->skill->prop = 0;
				$public_value->skill->diamond = 0;
			}
			if($type == 1)//分身
			{
				$public_value->skill->diamond ++;
			}
			else //复制
			{
				$public_value->skill->prop ++;
			}
			$sql = "update ".$sql_table."user_data set public_value='".json_encode($public_value)."' where gameid='".$skillResult['gameid']."'";
			$conne->uidRst($sql);
		}
		
		

		$returnData->skillendtime = $endTime;
		$returnData->logendtime = $copyTime;
		$userData->write2DB();
	}while(false)	
	



?> 