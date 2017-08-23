<?php 
	require_once($filePath."cache/monster.php");
	$changeFightDataValue = new stdClass();
	//���������������
	function renewMyCard(){
		global $userData,$stopWriteDB,$returnData,$filePath,$pkTaskData;
		$myCard = $userData->pk_common->my_card[0];
		$myCard->num --;
		$task = $myCard->task;
		$len = count($pkTaskData);
		if($task && $task->current < $task->num && $len)//����
		{
			if($task->type == 1)
			{
				if($pkTaskData[0]->monsterID == $task->mid)
					$task->current ++;
			}
			else if($task->type == 4)
			{
				if($returnData->result && $pkTaskData[$len-1]->monsterID == $task->mid)
					$task->current ++;
			}
			else if($task->type == 6)
			{
				if($returnData->result)
				{
					$list = $returnData->team1base->list;
					$len = count($list);
					for($i=0;$i<$len;$i++)
					{
						if($list[$i] == $task->mid)
						{
							$task->current ++;
							break;
						}
					}
				}
			}
			else 
			{
				$kill = 0;
				for($i=0;$i<$len;$i++)
				{
					$player = $pkTaskData[$i];
					if($player->monsterID == $task->mid)
					{
						$kill ++;
						if($task->type == 5)
							$task->current ++;
						else if($task->type == 2 && $kill == 2)
							$task->current ++;
						else if($task->type == 3 && $kill == 3)
							$task->current ++;
					}
					else
					{
						$kill = 0;
					}
				}
				
			}
			
			if($task->current >= $task->num)//�������
			{
				require_once($filePath."get_monster_collect.php");
				switch($task->award_type)
				{
					case 'diamond':
						$userData->addDiamond($task->award_value);
						break;
					case 'coin':
						$userData->addCoin($task->award_value);
						break;
					case 'card':
						addMonsterCollect($task->award_value);
						break;
					case 'energy':
						$userData->addEnergy($task->award_value);
						break;
					case 'ticket':
						$userData->addProp(21,$task->award_value);
						break;
				}
				$returnData->finish_task = new stdClass();
				$returnData->finish_task->{$task->award_type} = $task->award_value;
			}
			
			
		}
		
		
		
		
		if($myCard->num <= 0)
		{
			$stopWriteDB = true;
			require_once($filePath."get_my_card.php");
			$returnData->get_new_card = true;
		}
		else
		{
			$returnData->sync_my_card = $userData->pk_common->my_card;
		}
	}
	
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
				$count += $i;//ceil(($i + 1)/10); 
			}
			return $count;
		}
	}
	
	function getLeaderLevel($mid){
		global $userData;
		if(!$userData->tec->leader->{$mid})
			return 0;
		$leaderExp = $userData->tec->leader->{$mid};
		
		for($i=1;$i<=30;$i++)
		{
			$exp = floor(pow($i,10/3.5) + 40*$i);
			if($leaderExp < $exp)
				return $i - 1;
		}
		return 30;
	}

	////������������PK���� ��ҵ�ѡ��$typeս������
	function changePKData($data,$type,$isEqual=false,$chooseListIn = null,$hardLevel = 0){
		//$data��{list:[],ring:1,index:0}
		//out {list:[],ring:{id:1,level:1},tec:{id:"hp,sp,atk",...},fight:0}  fight:ս���ӳɣ�ǰ����ǻ����ӳ�
		global $userData,$monster_base,$HardBase;
		$outData = new stdClass();
		if($chooseListIn)
		{
			$chooseList = $chooseListIn;
		}
		else 
		{
			if($type == 'main_game' || $type == 'server_game' || $type == 'server_game_equal' || $type == 'my_card')
				$chooseList = $userData->pk_common->my_card;
			else
				$chooseList = $userData->{$type}->choose;
			if(!$chooseList)
			{
				$outData->fail = 110;
				return $outData;
			}
		}
		if(!property_exists($data,'index'))
			$data->index = 0;

		if($data->index){
			$outData->index = $data->index;
			$chooseList = $chooseList[$data->index];
		}
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
		// $outData->ring = new stdClass();
		// $outData->ring->id = $data->ring;	
		$outData->fight = 0;
		$outData->force = $force;
		$len = count($data->list);
		
		if(!$isEqual)//����Ƽ�Ӱ��
		{
			$outData->fight = $force;
			// $outData->ring->level = 0;
			// if(isset($userData->tec->ring->{$data->ring}))
				// $outData->ring->level = $userData->tec->ring->{$data->ring};
				
			// 16�˺���ǿ��17������ǿ��18�ظ���ǿ��19���Ƽ�ǿ��20����ѹ��
			// $outData->stec = new stdClass();//����Ƽ���16-20��
			// for($i=16;$i<=20;$i++)
			// {
				// $v = 0;
				// if(isset($userData->tec->main->{$i}))
					// $v = $userData->tec->main->{$i};
				// if($v)
					// $outData->stec->{'t'.$i} = $v;	
			// }
			
			
			$outData->tec = new stdClass();
			$outData->leader = new stdClass();
			$outData->mlevel = new stdClass();
				
			//��ʼ��������ӳ�
			//ͨ��   13������14Ѫ����15�ٶȣ�1-12��Ӧ���Լ�ǿ��12������Ѫͬ�ӣ�
			$comment = array('hp'=>$force,'atk'=>$force,'spd'=>0);
			// if(isset($userData->tec->main->{'13'}))
				// $comment['atk'] += getTecAdd('main',$userData->tec->main->{'13'});
			// if(isset($userData->tec->main->{'14'}))
				// $comment['hp'] += getTecAdd('main',$userData->tec->main->{'14'});
			// if(isset($userData->tec->main->{'15'}))
				// $comment['spd'] += getTecAdd('main',$userData->tec->main->{'15'});
				
			
			for($i=0;$i<$len;$i++)
			{
				$monsterID = $data->list[$i];
				$vo = $monster_base[$monsterID];
				if(!isset($outData->tec->{$monsterID}))
				{
					
					if($userData->tec->monster->{$monsterID})
						$outData->mlevel->{$monsterID} = $userData->tec->monster->{$monsterID};
					$tecAdd = getTecAdd('monster',$userData->tec->monster->{$monsterID});
					if($tecAdd)
						$outData->tec->{$monsterID} = $tecAdd;
						
					$leaderLevel = getLeaderLevel($monsterID);
					if(!$outData->leader->{$vo['mtype']} || $leaderLevel > $outData->leader->{$vo['mtype']})	
						$outData->leader->{$vo['mtype']} = $leaderLevel;
						
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
		
		if($hardLevel)//������
		{
			$forceLimit = $HardBase['force'][$hardLevel];
			$levelLimit = $HardBase['level'][$hardLevel];
			$leaderLimit = $HardBase['leader'][$hardLevel];
			
			
			if($outData->fight > $forceLimit)
				$outData->fight = $forceLimit;
			foreach($outData->mlevel as $key=>$value)
			{
				if($value > $levelLimit)
					$outData->mlevel->{$key} = $levelLimit;
			}
			foreach($outData->leader as $key=>$value)
			{
				if($value > $leaderLimit)
					$outData->leader->{$key} = $leaderLimit;
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