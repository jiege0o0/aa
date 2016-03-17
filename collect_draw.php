<?php 
	
	$times = $msg->times;
	do{
		$propID = 22;
		$need = $times*5;
		if($userData->getPropNum($propID) < $need)//不够￥
		{
			$returnData->fail = 1;
			$returnData->sync_prop = new stdClass();
			$returnData->sync_prop->{$propID} = $userData->prop->{$propID};
			break;
		}
		require_once($filePath."cache/monster.php");
		$userData->addProp($propID,-$need);
		
		$award = new stdClass();
		
		$list = array();
		//生成碎片列表
		foreach($monster_base as $key=>$value)
		{
			array_push($list,$key);
		}
		$len = count($list) - 1;
		
		while($times--)
		{
			$id = $list[rand(0,$len)];
			if(!$award->{$id})
			{
				$award->{$id} = 0;
			}
			
			$award->{$id} ++;
			$userData->addCollect($id,1);
		}
		
		$userData->write2DB();	
		$returnData->award = $award;
	}while(false);
	
	
	//取一个碎片
	function getAward(){
	
	}
	


?> 