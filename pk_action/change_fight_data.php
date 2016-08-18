<?php 
	require_once($filePath."cache/monster.php");
	$changeFightDataValue = new stdClass();
	//合法性检测
	function isMonsterDataError($data,$fromList,$isEqual){
		//$data：{list:[],ring:1}
		//$fromList{"list":[102,302,501,203,105,107,307,104,502,101],"ring":[1,16]}
		global $monster_base,$userData,$changeFightDataValue;
		$cost = 0;
		$repeatNum = array();
		

		if(count($data->list) > 6)
			return 107;//总数量不对
		foreach($data->list as $key=>$value)
		{
		
			if(!in_array($value,$fromList->list,true))
			{
				return 106;//没这个宠物
			}
			$monster = $monster_base[$value];
			$needCost = $repeatNum[$value];
			if(!$needCost)
				$needCost = $monster['cost'];
			$cost += $needCost;
			$repeatNum[$value] = ceil($needCost*1.1);
		}
		if($cost > 88)
			return 104;
		$changeFightDataValue->cost = $cost;
		$changeFightDataValue->chooseList = $fromList;
		return 0;
	}
	
	//得到加成的数值
	function getTecAdd($type,$level=0){
		if(!$level)
			return 0;
		if($type == 'speed')
			return ceil($level/3);
		if($type == 'main')
			return $level;
		if($type == 'monster')
		{
			$count = 0;
			for($i=1;$i<=$level;$i ++)
			{
				$count += ceil(($i + 1)/10); 
			}
			return $count;
		}
	}

	////用玩家数据填充PK数据 玩家的选择，$type战斗类型
	function changePKData($data,$type,$isEqual=false,$chooseListIn = null){
		//$data：{list:[],ring:1,index:0}
		//out {list:[],ring:{id:1,level:1},tec:{id:"hp,sp,atk",...},fight:0}  fight:战斗加成，前面的是基础加成
		global $userData,$monster_base;
		$outData = new stdClass();
		if($chooseListIn)
		{
			$chooseList = $chooseListIn;
		}
		else
		{
			$chooseList = $userData->{$type}->choose;
			if(!$chooseList)
			{
				$outData->fail = 110;
				return $outData;
			}
		}
		if(!property_exists($data,'index'))
			$data->index = 0;

		if($data->index)
			$chooseList = $chooseList[$data->index];
		else 
			$chooseList = $chooseList[0];
			
		if(!$chooseList)
		{
			$outData->fail = 111;
			return $outData;
		}
		
		
		
		$error = isMonsterDataError($data,$chooseList,$isEqual);
		if($error)
		{
			$outData->fail = $error;
			return $outData;
		}
			
			
		$force = $userData->tec_force + $userData->award_force;
		$outData->list = $data->list;	
		$outData->ring = new stdClass();
		$outData->ring->id = $data->ring;	
		$outData->fight = 0;
		$outData->force = $force;
		$len = count($data->list);
		
		if(!$isEqual)//计算科技影响
		{
			// $outData->ring->level = 0;
			// if(isset($userData->tec->ring->{$data->ring}))
				// $outData->ring->level = $userData->tec->ring->{$data->ring};
				
			// 16伤害增强，17防御增强，18回复增强，19克制加强，20克制压制
			$outData->stec = new stdClass();//特殊科技（16-20）
			for($i=16;$i<=20;$i++)
			{
				$v = 0;
				if(isset($userData->tec->main->{$i}))
					$v = $userData->tec->main->{$i};
				if($v)
					$outData->stec->{'t'.$i} = $v;	
			}
			
			
			$outData->tec = new stdClass();
				
			//开始计算基础加成
			//通用   13攻击，14血量，15速度，1-12对应属性加强（12个，攻血同加）
			$comment = array('hp'=>$force,'atk'=>$force,'spd'=>0);
			if(isset($userData->tec->main->{'13'}))
				$comment['atk'] += getTecAdd('main',$userData->tec->main->{'13'});
			if(isset($userData->tec->main->{'14'}))
				$comment['hp'] += getTecAdd('main',$userData->tec->main->{'14'});
			if(isset($userData->tec->main->{'15'}))
				$comment['spd'] += getTecAdd('main',$userData->tec->main->{'15'});
				
			
			for($i=0;$i<$len;$i++)
			{
				$monsterID = $data->list[$i];
				$vo = $monster_base[$monsterID];
				if(!isset($outData->tec->{$monsterID}))
				{
					$outData->tec->{$monsterID} = getTecAdd('monster',$userData->tec->monster->{$monsterID});;
					// $add = 0;
					// if(isset($userData->tec->monster->{$monsterID}))
						// $add = getTecAdd('monster',$userData->tec->monster->{$monsterID});
					// $typeAdd = 0;
					// if(isset($userData->tec->main->{$vo['type']}))
						// $typeAdd = getTecAdd('main',$userData->tec->main->{$vo['type']});
					// $outData->tec->{$monsterID}->hp = $comment['hp'] + $add + $typeAdd;
					// $outData->tec->{$monsterID}->atk = $comment['atk'] + $add + $typeAdd;
					// $outData->tec->{$monsterID}->spd = $comment['spd'];// + $addSpeed + $typeAddSpeed; 不加成速度了
				}
			}
		}
		
		//计算过载，位置
		// $outData->index = new stdClass();
		// $monsterNum = array();
		// for($i=0;$i<$len;$i++)
		// {
			// $monsterID = $data->list[$i];
			// if(isset($monsterNum[$monsterID]))
				// continue;
			// $monsterNum[$monsterID] = 1;
			// $offset=array_search($monsterID,$data->list);
			// $outData->index->{$monsterID} = $offset;
			// if($userData->getCollectLevel($monsterID) == 4)
			// {
				// if($monster_base[$monsterID]['wood'])
					// $outData->fight += 5;
				// else
					// $outData->fight += 2;
			// }
				
		// }
		// if(count($monsterNum)*2 > $len)
			// $outData->fight -= 8;
		
		return $outData;	
			
			
	}
	
	
	
	
	
	
	
	
?> 