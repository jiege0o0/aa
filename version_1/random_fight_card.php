<?php 
	require_once($filePath."cache/monster.php");

	
	function randomFightCard($level){
		global $monster_base;
		$arr = array();
		foreach($monster_base as $key=>$value)
		{
			if($level >= $value['level'])
			{	
				array_push($arr,$value);
			}
		}
		
		$count = 0;
		$cost = 0;
		$returnArr = array();
		$runIndex = 0;
		$len = count($arr) - 1;
		$addTime = 0;
		while(true)
		{
			$vo = $arr[rand(0,$len)];
			if($cost + $vo['cost'] <= 88)
			{
				array_push($returnArr,$vo['id']);
				$count += 1;
				$cost += $vo['cost'];
			}
			else
				$addTime ++;
			if($cost >= 88-$runIndex)
			{
				break;
			}
			if($runIndex > 30)
			{
				break;
			}
			if($count >= 6 || $addTime > 10)
			{
				$returnArr = array();
				$count = 0;
				$cost = 0;
				$addTime = 0;
				$runIndex+= 0.2;
				
			}
		}
		usort($returnArr,randomSortFun);
		return $returnArr;
	}		
?> 