<?php 
	require($filePath."cache/monster.php");
	
	$taskID = $msg->taskid;
	$task = $userData->active->task;
	$line = $task_base[$taskID]['line'];
	$finishTask = $userData->active->task->{$line};
	
	
	
	
	$arr = array();
	foreach($task_base ad $key=>$value)
	{
		if($value['line'] == $line)
		{
			$temp = explode('_',$key,2);
			$value['index'] = ((int)$temp[0])*1000 + ((int)$temp[1]);
			array_push($arr,$value);
		}
	}
	
	function sortByIndex($a,$b){
		if($a['index'] < $b['index'])
			return -1;
		return 1;
	}
	
	usort($arr,sortByIndex);
	
	do{
		//是不是正在进行的任务
		if($finishTask)
		{
			$b = false;
			$len = count($arr);
			for($i=0;$i<$len;$i++)
			{
				if($arr[$i]['id'] == $finishTask)
				{
					if($arr[$i+1]['id'] == $taskID)
						$b = true;
					break;
				}
			}
			if(!$b)
			{
				$returnData->fail = 1;
				break;
			}	
		}
		else
		{
			if($taskID != $arr[0]['id'])
			{
				$returnData->fail = 1;
				break;
			}
		}
		
		$taskVO = $task_base[$taskID];
		//任务前置条件
		if($taskVO['level'])
		{
			if($line = 2 && $userData->level < $taskVO['level'])
			{
				$returnData->fail = 2;
				break;
			}
		}
		
		//任务是否完成
		$isFinsih = false;
		$stat = $userData->active->task->stat;
		$value1 = $taskVO['value1'];
		$value2 = $taskVO['value2'];
		
		if(!$stat)
			$stat = new stdClass();
		switch($taskVO['coin'])
		{
			case 'draw':
                if($stat->{'draw'})
                    $isFinsih = true;
                break;
            case 'main_game':
                $isFinsih = $userData->main_game->level >= $value1;
                break;
            case 'force':
                $isFinsih = $userData->main_game->getForce() >= $value1;
                break;
            case 'map_game':
				if($userData->pk_common->map)
				{
					if($value1 < 0)
						$isFinsih = $userData->pk_common->map->lasttime > 0;
					else
						$isFinsih = $userData->pk_common->map->max_level >= $value1;
				}
                break;
            case 'main_award':
                if($stat->{'award'})
					$isFinsih = true;
                break;
            case 'server_game':
                if($value1 < 0)
					$isFinsih = $userData->server_game->total > 0;
				else
				{
					require_once($filePath."pk_action/pk_tool.php");
					$pkLevel = getPKTableLevel($userData->server_game->exp,100);
					$isFinsih = $pkLevel >= $value1;
				}
                break;
            case 'map_game_pk':
                $currentValue = $userData->pk_common->map->step;
                if($userData->pk_common->map->max_level> 0)
                    $currentValue = 10;
				$isFinsih = $currentValue >= $value1;
                break;
            case 'map_game_buy':
                if($stat->{'map_buy'])
					$isFinsih = true;
                break;
            case 'map_game_next':
                $isFinsih = $userData->pk_common->map->max_level > 0;
                break;
            case 'honor':
                foreach($userData->honor->monster as $key=>$value)
				{
					$step = (int)$value->a;
					if($step)
					{
						$isFinsih = true;
						break;
					}
				}
                break;
            case 'comment':
                if($stat->{'comment'})
					$isFinsih = true;
                break;
            case 'buy_ticket':
                if($stat->{'ticket'})
					$isFinsih = true;
                break;
            case 'server_equal_game':
                if($value1 < 0)
					$isFinsih = $userData->server_game_equal->total > 0;
				else
				{
					require_once($filePath."pk_action/pk_tool.php");
					$pkLevel = getPKTableLevel($userData->server_game_equal->exp,100);
					$isFinsih = $pkLevel >= $value1;
				}
                break;
            case 'card':
				$mLevel = $userData->tec->monster->{$value1};
				if($mLevel)
					$isFinsih = $mLevel >= $value2;
                break;
            case 'day_game':
               if(isSameDate($userData->day_game->lasttime))
				{
					$isFinsih = $userData->day_game->level >= $value1;
				}
                break;
            case 'friend':
                if($stat->{'friend'})
					$isFinsih = true;
                break;
            case 'friend_dungeon':
                if($stat->{'friend_dungeon'})
					$isFinsih = true;
                break;
		}
		if(!$isFinsih)
		{
			$returnData->fail = 3;
			break;
		}
		
		//发放奖励
		if($taskVO['diamond'])
			$userData->addDiamond($taskVO['diamond']);
		if($taskVO['coin'])
			$userData->addCoin($taskVO['coin']);
		if($taskVO['card'])
		{
			require_once($filePath."get_monster_collect.php");
			addMonsterCollect($taskVO['card']);
		}
		
		$userData->active->task->{$line} = $taskID;
		$returnData->sync_task = array();
		$returnData->sync_task[$line] = $taskID;
		$userData->setChangeKey('active');
		
		$userData->write2DB();	
	
	}while(false);

?> 