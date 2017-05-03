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
		while(true)
		{
			usort($arr,randomSortFun);
			$vo = $arr[0];
			if($cost + $vo['cost'] < 100)
			{
				array_push($returnArr,$vo['id']);
				$count += 1;
				$cost += $vo['cost'];
			}
			if($cost > 80)
			{
				break;
			}
			if($count > 6)
			{
				$returnArr = array();
				$count = 0;
				$cost = 0;
			}
		}
		usort($returnArr,randomSortFun);
		return $returnArr;
	}		
?> 