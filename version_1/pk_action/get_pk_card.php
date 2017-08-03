<?php 
	require_once($filePath."cache/monster.php");
	
	//取玩家能用的怪物组合
	function getPKCard($userLevel){
		global $monster_base;
		
		//得到用到的5个属性宠物
		$levelArr1 = array();
		$levelArr2 = array();
		$levelArr3 = array();
		foreach($monster_base as $key=>$value)
		{
			if($userLevel >= $value['level'])
			{	
				if($value['cost'] < 10)
					array_push($levelArr1,$key);
				else if($value['cost'] < 20)
					array_push($levelArr2,$key);
				else
					array_push($levelArr3,$key);
			}
		}
		usort($levelArr1,randomSortFun);
		usort($levelArr2,randomSortFun);
		usort($levelArr3,randomSortFun);
		
		$returnMonsterArr = array();//返回的宠物数据
		for($i=0;$i<2;$i++)
		{
			$id = array_pop($levelArr1);
			if($id)
				array_push($returnMonsterArr,$id);
				
			$id = array_pop($levelArr2);
			if($id)
				array_push($returnMonsterArr,$id);
				
			$id = array_pop($levelArr2);
			if($id)
				array_push($returnMonsterArr,$id);
				
			$id = array_pop($levelArr3);
			if($id)
				array_push($returnMonsterArr,$id);
		}
		
		//不足，要补
		if(count($returnMonsterArr) < 8)
		{
			$newArr = array_merge($levelArr1,$levelArr2,$levelArr3);
			usort($newArr,randomSortFun);
			while(count($returnMonsterArr) < 8 && count($newArr) > 0)
			{
				$id = array_pop($newArr);
				if($id)
					array_push($returnMonsterArr,$id);
			}
		}
		// array_push($returnMonsterArr,$levelArr1[0]);
		// array_push($returnMonsterArr,$levelArr1[1]);
		// array_push($returnMonsterArr,$levelArr2[0]);
		// array_push($returnMonsterArr,$levelArr2[1]);
		// array_push($returnMonsterArr,$levelArr2[2]);
		// array_push($returnMonsterArr,$levelArr2[3]);
		// array_push($returnMonsterArr,$levelArr3[0]);
		// array_push($returnMonsterArr,$levelArr3[1]);
		
		//乱序
		// usort($returnMonsterArr,randomSortFun);
		$obj = new stdClass();
		$obj->list = $returnMonsterArr;
		return $obj;
	}

	
?> 