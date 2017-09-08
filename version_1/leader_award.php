<?php 
	require_once($filePath."cache/monster.php");
	$ids=$msg->ids;

	do{
		if(!$userData->tec->leader->list)
		{
			$returnData->fail = 1;
			break;
		}
		$listLen = count($userData->tec->leader->list);
		$len = count($ids);
		if($listLen == 2 && $len != 1)
		{
			$returnData->fail = 2;
			break;
		}
		if($listLen == 6 && $len != 2)
		{
			$returnData->fail = 2;
			break;
		}
		
		for($i = 0;$i<$len;$i++)
		{
			$mid = $ids[$i];
			if($len == 1)
			{
				$userData->addLeaderExp($mid,50);
			}
			else if($userData->tec->leader->list[0] == $mid){
				$userData->addLeaderExp($mid,200);
			}
			else{
				$userData->addLeaderExp($mid,150);
			}
		}
		
		$userData->tec->leader->list = null;
		$userData->write2DB();	
		
	}while(false)	
	



?> 