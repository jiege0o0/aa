<?php 
	require_once($filePath."cache/monster.php");
	$changeFightDataValue = new stdClass();
	//�Ϸ��Լ��
	function isMonsterDataError($data,$fromList,$isEqual){
		//$data��{list:[],ring:1}
		//$fromList{"list":[102,302,501,203,105,107,307,104,502,101],"ring":[1,16]}
		global $monster_base,$userData,$changeFightDataValue;
		$cost = 0;
		$repeatNum = array();
		

		if(count($data->list) > 6)
			return 107;//����������
		foreach($data->list as $key=>$value)
		{
		
			if(!in_array($value,$fromList->list,true))
			{
				return 106;//û�������
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
	
	//�õ��ӳɵ���ֵ
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

	////������������PK���� ��ҵ�ѡ��$typeս������
	function changePKData($data,$type,$isEqual=false,$chooseListIn = null){
		//$data��{list:[],ring:1,index:0}
		//out {list:[],ring:{id:1,level:1},tec:{id:"hp,sp,atk",...},fight:0}  fight:ս���ӳɣ�ǰ����ǻ����ӳ�
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
		
		if(!$isEqual)//����Ƽ�Ӱ��
		{
			// $outData->ring->level = 0;
			// if(isset($userData->tec->ring->{$data->ring}))
				// $outData->ring->level = $userData->tec->ring->{$data->ring};
				
			// 16�˺���ǿ��17������ǿ��18�ظ���ǿ��19���Ƽ�ǿ��20����ѹ��
			$outData->stec = new stdClass();//����Ƽ���16-20��
			for($i=16;$i<=20;$i++)
			{
				$v = 0;
				if(isset($userData->tec->main->{$i}))
					$v = $userData->tec->main->{$i};
				if($v)
					$outData->stec->{'t'.$i} = $v;	
			}
			
			
			$outData->tec = new stdClass();
				
			//��ʼ��������ӳ�
			//ͨ��   13������14Ѫ����15�ٶȣ�1-12��Ӧ���Լ�ǿ��12������Ѫͬ�ӣ�
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
					// $outData->tec->{$monsterID}->spd = $comment['spd'];// + $addSpeed + $typeAddSpeed; ���ӳ��ٶ���
				}
			}
		}
		
		//������أ�λ��
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