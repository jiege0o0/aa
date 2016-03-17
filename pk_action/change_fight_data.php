<?php 
	require_once($filePath."cache/monster.php");
	$changeFightDataValue = new stdClass();
	//�Ϸ��Լ��
	function isMonsterDataError($data,$fromList){
		//$data��{list:[],ring:1}
		//$fromList{"list":[102,302,501,203,105,107,307,104,502,101],"ring":[1,16]}
		global $monster_base,$userData,$changeFightDataValue;
		$cost = 100;
		$wood = 1;
		$repeatNum = array();
		
		if(!in_array($data->ring,$fromList->ring))
			return 101;//û�������
		foreach($data->list as $key=>$value)
		{
		
			if(!in_array($value,$fromList->list))
			{
				return 106;//û�������
			}
			if(!$repeatNum[$value])
				$repeatNum[$value] = 0;
			$repeatNum[$value] ++;
			if($repeatNum[$value] > 3)//��������3��
				return 102;
			$monster = $monster_base[$value];
			//�ռ��ĳ���
			$cLevel = $userData->getCollectLevel($value);
			if($repeatNum[$value] > $cLevel)//�����ɳ�ս����Ƭ����
			{
				return 103;
			}
				
			
			//�۸񲻶�
			$cost -= $monster['cost'];
			$wood -= $monster['wood'];
			if($cost < 0)
				return 104;
			if($wood < 0)
				return 105;
		}
		$changeFightDataValue->cost = $cost;
		$changeFightDataValue->chooseList = $fromList;
		return 0;
	}
	
	//�õ��ӳɵ���ֵ
	function getTecAdd($type,$level=0){
		if(!$level)
			return 0;
		if($type == 'main')
			return $level;
		if($type == 'monster')
		{
			$count = 0;
			for($i=1;$i<=$level;$level ++)
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
		
		if($data->index)
			$chooseList = $chooseList[$data->index];
		else 
			$chooseList = $chooseList[0];
			
		if(!$chooseList)
		{
			$outData->fail = 111;
			return $outData;
		}
		
		
		
		$error = isMonsterDataError($data,$chooseList);
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
			$outData->ring->level = $userData->tec->ring->{$data->ring};
			if(!$outData->ring->level)
				$outData->ring->level = 0;
			// 16�˺���ǿ��17������ǿ��18�ظ���ǿ��19���Ƽ�ǿ��20����ѹ��
			$outData->stec = new stdClass();//����Ƽ���16-20��
			for($i=16;$i<=20;$i++)
			{
				$v = $userData->tec->main->{$i};
				if($v)
					$outData->stec->{'t'.$i} = $v;	
			}
			
			
			$outData->tec = new stdClass();
				
			//��ʼ��������ӳ�
			//ͨ��   13������14Ѫ����15�ٶȣ�1-12��Ӧ���Լ�ǿ��12������Ѫ��ͬ�ӣ�
			$comment = array('hp'=>$force,'atk'=>$force,'spd'=>$force);
			$comment['atk'] += getTecAdd($userData->tec->main->{'13'});
			$comment['hp'] += getTecAdd($userData->tec->main->{'14'});
			$comment['spd'] += getTecAdd($userData->tec->main->{'15'});
				
			
			for($i=0;$i<$len;$i++)
			{
				$monsterID = $data->list[$i];
				$vo = $monster_base[$monsterID];
				if(!$outData->tec->{$monsterID})
				{
					$outData->tec->{$monsterID} = new stdClass();
					$add = getTecAdd('monster',$userData->tec->monster->{$monsterID});
					$typeAdd = getTecAdd('main',$userData->tec->main->{$vo['type']});
					$outData->tec->{$monsterID}->hp = $comment['hp'] + $add + $typeAdd;
					$outData->tec->{$monsterID}->atk = $comment['atk'] + $add + $typeAdd;
					$outData->tec->{$monsterID}->spd = $comment['spd'] + $add + $typeAdd;
				}
			}
		}
		
		//������أ�λ��
		// $outData->index = new stdClass();
		$monsterNum = array();
		for($i=0;$i<$len;$i++)
		{
			$monsterID = $data->list[$i];
			if($monsterNum[$monsterID])
				continue;
			$monsterNum[$monsterID] = 1;
			$offset=array_search($monsterID,$data->list);
			// $outData->index->{$monsterID} = $offset;
			if($userData->getCollectLevel($monsterID) == 4)
				$outData->fight += 2;
		}
		if(count($monsterNum)*2 > $len)
			$outData->fight -= 8;
		
		return $outData;	
			
			
	}
	
	
	
	
	
	
	
	
?> 