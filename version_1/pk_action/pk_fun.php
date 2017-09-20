<?php
	$PKConfig = new stdClass();
	$PKConfig->actionRound = 15;
	$PKConfig->atkMP = 10;
	$PKConfig->defMP = 5;
	
	//A�Ƿ����B
	// function isRestrain($a,$b){
		// global $monster_kind;
		// $type1 = $a->monsterData['type'];
		// $type2 = $b->monsterData['type'];
		// return in_array($type2,$monster_kind[$type1]['restrain'],true);
	// }
	
	
	//PKһ�غϣ�ֱ����һ��λ����
	function pkOneRound($playArr1,$playArr2){
		global $pkData;
		$list = array_merge($playArr1,$playArr2);
		array_push($list,$pkData->team1->teamPlayer);
		array_push($list,$pkData->team2->teamPlayer);
		
		$pkData->step = 1;
		// trace(count($playArr2));
		$pkData->roundStart($playArr1,$playArr2);
		$pkData->dealFrontArray();
		// trace(count($pkData->playArr2));
		$result = $pkData->testRoundFinish();
		$pkData->out_gameStart();
		// $pkData->out_end();

		while(!$result){
			$result = pkOnePlayer($list);
		}
		
		$enemy = $playArr2[0];
		$self = $playArr1[0];
		$self->testTSkill('OVER');
		$enemy->testTSkill('OVER');
		if($pkData->dealTArray())//������Ч
			$pkData->out_end();
		debug('pkonefinish');
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
		if($a->id > $b->id)
			return -1;
		return 1;
	}
	
	function teamTestTSkill($team,$type){
		$len = count($team->currentMonster);
		for($i=0;$i<$len;$i++)
		{
			$team->currentMonster[$i]->testTSkill($type);
		}
	}
	
	//����һ����λ����
	function pkOnePlayer($list){
		global $pkData,$PKConfig;
		$pkData->step ++;
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
		
		$lastSkiller = $user;
		
		$user->buffAction('BEFORE');
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
					$user->testTSkill('SKILL');
					$user->testTSkill('ACTION');
					$pkData->dealTArray();//������Ч
					break;
				}
				
				if($user->stat[21] || $user->stat[24])//�����չ�
					break;
					
				
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
							$pkData->dealTArray();//������Ч
						}
					}
					if($skillOK)
					{
						$user->testTSkill('ACTION');
						$haveAction = true;
						break;
					}
				}
				
				
				pk_action_skill($user->atkAction,$user,$self,$enemy);
				$haveAction = true;
				$user->testTSkill('ACTION');
				$pkData->dealTArray();//������Ч
				break;

			}while(false);
			// if(!$haveSkill && $user->cdhp)//��ʱӰ��Ѫ
			// {
				// pk_cdhp($user);
			// }
			if($haveSkill || $haveAction)
			{
				$user->testTSkill('AFTER');
				$enemy->testTSkill('EAFTER');
			}
				
			$pkData->dealTArray();//������Ч
		}
		else
		{
			if(!($user->stat[21] || $user->stat[24]))//�����չ�
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
				
			
		}
		
		$self->testDie();
		$enemy->testDie();
		if(!$haveSkill && $user->setHaveAction($haveAction))
		{
			if($user->isPKing)
			{
				$pkData->dealTArray();//������Ч
				if($user->hp > 0 && $enemy->hp > 0)
					pk_kill($user,$enemy);
			}
		}
		else
		{
			$self->testDie();
			$enemy->testDie();
		}
		
		
		
		//������
		if($self->hp <= 0)
			$self->testTSkill('REALDIE');
		if($enemy->hp <= 0)
			$enemy->testTSkill('REALDIE');
		$pkData->dealTArray();//������Ч
		
		$user->setRoundEffect();
		// if($haveAction || $user->isPKing)//�ǻغ��е����
			$pkData->out_end($user);
			

		
		//����PK���
		return $pkData->testRoundFinish();
	}
?> 