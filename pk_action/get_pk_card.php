<?php 
	require_once($filePath."cache/monster.php");
	//随机排序
	function randomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
	
	//这个怪物是否合格
	function get_card_isMonsterRight($monster,&$recordData){
		if($recordData['num'] == 8 && $recordData['w'] == 0 && !$monster['wood'])
			return false;
		if($recordData['num'] == 9 && $recordData['w'] == 1 && !$monster['wood'])
			return false;
		if($monster['wood'] && $recordData['w'] >=3)
			return false;
		if($monster['collect'] == 0 && $recordData['c'] >=2)
			return false;
		if($monster['cost'] >= 20)
		{
			if($recordData['p3'] >=4)
				return false;
		}
		else if($monster['cost'] >= 10)
		{
			if($recordData['p2'] >=4)
				return false;
		}
		else
		{
			if($recordData['p1'] >=4)
				return false;
		}
		return true;
	}
	
	//怪物数据填充记录
	function get_card_setRecordData($monster,&$recordData){
		$recordData['num'] ++;
		if($monster['wood'])
			$recordData['w'] ++;
		if($monster['collect'] == 0)
			$recordData['c'] ++;
		if($monster['cost'] >= 20)
			$recordData['p3'] ++;
		else if($monster['cost'] >= 10)
			$recordData['p2'] ++;
		else
			$recordData['p1'] ++;
	}
	//取玩家能用的怪物组合
	function getPKCard($userLevel){
		global $monster_base,$monster_kind;
		
	
		
		//3个档位，每档不能超过4个，木怪2-3个， 高级碎片怪不超过2个
		$recordData = array('p1'=>0,'p2'=>0,'p3'=>0,'w'=>0,'c'=>0,'num'=>0);
		
		
		//得到用到的5个属性宠物
		$levelArr = array();
		foreach($monster_kind as $key=>$value)
		{
			if($userLevel >= $value['level'])
			{	
				array_push($levelArr,$key);
			}
		}
		usort($levelArr,randomSortFun);
		
		$returnMonsterArr = array();//返回的宠物数据
		//有令牌的宠物
		$ringID = $levelArr[0];
		$monsterList = $monster_kind[$ringID]['list']; 
		usort($monsterList,randomSortFun);
		while(count($returnMonsterArr) < 3 && count($monsterList) > 0)
		{
			$monster = $monster_base[array_pop($monsterList)];
			if(!$monster)
				break;
			if(get_card_isMonsterRight($monster,$recordData))
			{
				array_push($returnMonsterArr,$monster['id']);
				get_card_setRecordData($monster,$recordData);
			}
		}
		
		//合并所有数据
		$len = min(5,count($levelArr));
		for($i=1;$i<$len;$i++)
		{
			$monsterList = array_merge($monsterList,$monster_kind[$levelArr[$i]]['list']);
		}
		//返回剩下的
		usort($monsterList,randomSortFun);
		while(count($returnMonsterArr) < 10 && count($monsterList) > 0)
		{
			$monster = $monster_base[array_pop($monsterList)];
			if(get_card_isMonsterRight($monster,$recordData))
			{
				array_push($returnMonsterArr,$monster['id']);
				get_card_setRecordData($monster,$recordData);
			}
		}
		
		//乱序
		usort($returnMonsterArr,randomSortFun);
		$obj = new stdClass();
		$obj->list = $returnMonsterArr;
		$obj->ring = array($ringID,rand(13,24));
		return $obj;
	}

	
?> 