<?php 
	do{
		if(!$userData->active->guess)
			$userData->active->guess = new stdClass();
		if($userData->active->guess->list1)
		{
			$returnData->fail = 1;//已有数据
			break;
		}
		$level = $userData->level;
		$arr = array();
		for($i=0;$i<3 && $level > 0;$i++)
		{
			require_once($filePath."cache/guess_".$level.".php");
			$level --;
			$arr = array_merge($arr,$guess_base);
		}
		
		$len = count($arr);
		debug($len);

		
		$userData->active->guess->list1 = $arr[rand(0,$len - 1)];
		$userData->active->guess->list2 = $arr[rand(0,$len - 1)];

		
		$userData->setChangeKey('active');
		$userData->write2DB();	
		
		$returnData->list1 = $userData->active->guess->list1;
		$returnData->list2 = $userData->active->guess->list2;
	}while(false);
		
?> 