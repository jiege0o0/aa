<?php
	$PKConfig = new stdClass();
	$PKConfig->skillMP = 100;
	$PKConfig->maxMP = 150;
	$PKConfig->actionRound = 30;
	$PKConfig->atkMP = 15;
	$PKConfig->defMP = 10;
	
	//A�Ƿ����B
	function isRestrain($a,$b){
		global $monster_kind;
		$type1 = $a->monsterData['type'];
		$type2 = $b->monsterData['type'];
		return in_array($type2,$monster_kind[$type1]['restrain'],true);
	}
	
	
	//PKһ�غϣ�ֱ����һ��λ����
	function pkOneRound($playArr1,$playArr2){
		global $pkData;
		$list = array_merge($playArr1,$playArr2);
		array_push($list,$pkData->team1->teamPlayer);
		array_push($list,$pkData->team2->teamPlayer);
		
		$pkData->roundStart($playArr1,$playArr2);
		$pkData->dealTArray();
		$result = $pkData->testRoundFinish();

		while(!$result){
			$result = pkOnePlayer($list);
		}
		$pkData->roundFinish($result);
	}
	
	function cdCountSortFun($a,$b){
		if($a->mpAction || $b->mpAction)//��������
		{
			if($a->mpAction > $b->mpAction)
				return -1;
			if($a->mpAction < $b->mpAction)
				return 1;
		}
		else//CD����
		{	
			if($a->cdCount < $b->cdCount)
				return -1;
			if($a->cdCount > $b->cdCount)
				return 1;
		}

		
		if($a->speed > $b->speed)
				return -1;
		if($a->speed < $b->speed)
				return 1;
		if($a->joinRound > $b->joinRound)
			return -1;
		if($a->joinRound < $b->joinRound)
			return 1;
		if($a->id < $b->id)
			return -1;
		if($a->id > $b->id)
			return 1;
	}
	
	//����һ����λ����
	function pkOnePlayer($list){
		global $pkData,$PKConfig;
		// $pkingPlayer = array('1'=>$pkData->team1->currentMonster[0],'2'=>$pkData->team2->currentMonster[0]);
		$len = count($list);
		$user = null;
		for($i=0;$i<$len;$i++)
		{
			$player = $list[$i];
			$player->setCDCount();
			// if($player->isPKing)
			// {
				// trace($player->id.'->'.$player->base_speed.'->'.$player->add_speed);
			// }
			
			
			if(!$user || cdCountSortFun($user,$player) == 1)
				$user = $player;
		}
		
		
		//�õ�Ҫ�ж��ĵ�λ
		
		$enemy = $user->team->enemy->currentMonster[0];
		$self = $user->team->currentMonster[0];

		$haveAction = false;//�Ƿ����ж�
		$haveSkill = false;//�Ƿ��д��У������ǲ岥�������ж��غ�

		if($user->isPKing)//���ϵĵ�λ
		{
			//սǰ������Ч
			$user->testTSkill('BEFORE');
			$pkData->dealTArray();
			do{
				if($user->mpAction)//����
				{
					$skillOK = pk_action_skill($user->skill,$user,$self,$enemy);
					$haveSkill = true;
					$user->testTSkill('SKILL3');
					$user->testTSkill('SKILL4');
					$enemy->testTSkill('ESKILL3');
					$enemy->testTSkill('ESKILL4');
					$pkData->dealTArray();//������Ч
					break;
				}
				
				$arr = $user->getSkill();//����ʹ�õ�С��
				$user->addMP = true;
				$len = count($arr);
				if($len > 0)
				{
					$skillOK = false;
					for($i=0;$i<$len;$i++){
						$value = $arr[$i];
						if(pk_action_skill($value,$user,$self,$enemy))
						{
							$skillOK = true;
							$user->testTSkill('SKILL2');
							$user->testTSkill('SKILL4');
							$enemy->testTSkill('ESKILL2');
							$enemy->testTSkill('ESKILL4');
							$pkData->dealTArray();//������Ч
						}
					}
					if($skillOK)
					{
						$haveAction = true;
						break;
					}
				}
				
				
				if($user->action1 <= 0 && $user->action5 <= 0)//�չ�
				{
					pk_atk($user,$enemy);
					$haveAction = true;
					$user->testTSkill('SKILL1');
					$user->testTSkill('SKILL4');
					$enemy->testTSkill('ESKILL1');
					$enemy->testTSkill('ESKILL4');
					$pkData->dealTArray();//������Ч
					break;
				}
			}while(false);
			if(!$haveSkill && $user->cdhp)//��ʱӰ��Ѫ
			{
				pk_cdhp($user);
			}
			$user->testTSkill('AFTER');
			$pkData->dealTArray();//������Ч
		}
		else
		{
			$arr = $user->getSkill();//����ʹ�õ�С��
			$len = count($arr);
			if($len > 0)
			{
				for($i=0;$i<$len;$i++){
					$value = $arr[$i];
					if(pk_action_skill($value,$user,$self,$enemy))
					{
						$haveAction = true;
						$pkData->dealTArray();//������Ч
					}
				}
			}
		}
		
		if(!$haveSkill && $user->setHaveAction($haveAction))
		{
			pk_kill($user,$enemy);
		}
		
		$user->setRoundEffect();
		if($haveSkill || $haveAction || $user->isPKing)
			$pkData->out_end($user);
			

		
		//����PK���
		return $pkData->testRoundFinish();
	}
?> 