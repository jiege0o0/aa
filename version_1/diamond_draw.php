<?php 
	require_once($filePath."cache/monster.php");
	do{
		$time = time();
		if(!$userData->active->draw_time)
			$userData->active->draw_time = 0;
		if($time - $userData->active->draw_time < 4*3600)
		{
			$returnData->fail = 1;
			$returnData->sync_active = new stdClass();
			$returnData->sync_active->draw_time = $userData->active->draw_time;
			$returnData->sync_active->draw_num = $userData->active->draw_num;
			break;
		}
		
		if(!isSameDate($userData->active->draw_time))
		{
			$userData->active->draw_num = 0;
		}
		
		if($userData->active->draw_num == 3)
		{
			$returnData->fail = 2;
			$returnData->sync_active = new stdClass();
			$returnData->sync_active->draw_time = $userData->active->draw_time;
			$returnData->sync_active->draw_num = $userData->active->draw_num;
			break;
		}
			
		$cost = 15;
		while(lcg_value()>0.5)
			$cost += 5;
		$arr = array();
		foreach($monster_base as $key=>$value)
		{
			if($userData->level >= $value['level'] && $value['cost'] <= $cost)
			{	
				array_push($arr,$value);
			}
		}
		$value = $arr[rand(0,count($arr) - 1)];
		$returnData->cardid = $value['id'];
		$userData->addTaskStat('draw');
		$userData->addDiamond($value['cost']);
		$userData->active->draw_time = $time;
		$userData->active->draw_num ++;
		$returnData->sync_active = new stdClass();
		$returnData->sync_active->draw_time = $userData->active->draw_time;
		$returnData->sync_active->draw_num = $userData->active->draw_num;
		$userData->setChangeKey('active');
		$userData->write2DB();
	}while(false);
	


?> 