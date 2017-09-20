<?php
	$PKConfig = new stdClass();
	$PKConfig->actionRound = 15;
	$PKConfig->atkMP = 10;
	$PKConfig->defMP = 5;
	
	//A是否克制B
	// function isRestrain($a,$b){
		// global $monster_kind;
		// $type1 = $a->monsterData['type'];
		// $type2 = $b->monsterData['type'];
		// return in_array($type2,$monster_kind[$type1]['restrain'],true);
	// }
	
	
	//PK一回合，直到有一单位死掉
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
		if($pkData->dealTArray())//特性生效
			$pkData->out_end();
		debug('pkonefinish');
		$pkData->roundFinish($result);
	}
	
	function cdCountSortFun($a,$b){
		if($a->mpAction || $b->mpAction)//能量出手
		{
			if($a->mpAction > $b->mpAction)
				return -1;
			if($a->mpAction < $b->mpAction)
				return 1;
		}
		else//CD出手
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
	
	//其中一个单位出手
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
		
		//得到要行动的单位
		
		$enemy = $user->team->enemy->currentMonster[0];
		$self = $user->team->currentMonster[0];

		$haveAction = false;//是否有行动
		$haveSkill = false;//是否有大招，由于是插播，不算行动回合
		
		$lastSkiller = $user;
		
		$user->buffAction('BEFORE');
		if($user->isPKing)//场上的单位
		{
			//战前特性生效
			$user->testTSkill('BEFORE');
			$pkData->dealTArray();
			do{
				if($user->mpAction)//出大技
				{
					$skillOK = pk_action_skill($user->skill,$user,$self,$enemy);
					$haveSkill = true;
					$user->testTSkill('SKILL');
					$user->testTSkill('ACTION');
					$pkData->dealTArray();//特性生效
					break;
				}
				
				if($user->stat[21] || $user->stat[24])//不可普攻
					break;
					
				
				$arr = $user->getSkill();//可以使用的小技
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
							$pkData->dealTArray();//特性生效
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
				$pkData->dealTArray();//特性生效
				break;

			}while(false);
			// if(!$haveSkill && $user->cdhp)//当时影响血
			// {
				// pk_cdhp($user);
			// }
			if($haveSkill || $haveAction)
			{
				$user->testTSkill('AFTER');
				$enemy->testTSkill('EAFTER');
			}
				
			$pkData->dealTArray();//特性生效
		}
		else
		{
			if(!($user->stat[21] || $user->stat[24]))//不可普攻
			{
				$arr = $user->getSkill();//可以使用的小技
				$len = count($arr);
				if($len > 0)
				{
					for($i=0;$i<$len;$i++){
						$value = $arr[$i];
						if(pk_action_skill($value,$user,$self,$enemy))
						{
							$haveAction = true;
							$pkData->dealTArray();//特性生效
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
				$pkData->dealTArray();//特性生效
				if($user->hp > 0 && $enemy->hp > 0)
					pk_kill($user,$enemy);
			}
		}
		else
		{
			$self->testDie();
			$enemy->testDie();
		}
		
		
		
		//真正死
		if($self->hp <= 0)
			$self->testTSkill('REALDIE');
		if($enemy->hp <= 0)
			$enemy->testTSkill('REALDIE');
		$pkData->dealTArray();//特性生效
		
		$user->setRoundEffect();
		// if($haveAction || $user->isPKing)//非回合中的玩家
			$pkData->out_end($user);
			

		
		//返回PK结果
		return $pkData->testRoundFinish();
	}
?> 