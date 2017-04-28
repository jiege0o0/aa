<?php 

	//随机排序
	function randomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
	
	//更换卡牌
	$level = $msg->level;
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
	
	$force = 0;
	
	$returnData->sync_map = $choose;
		
?> 