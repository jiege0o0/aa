<?php
	$PKConfig = new stdClass();
	$PKConfig->skillMP = 100;
	$PKConfig->maxMP = 150;
	$PKConfig->actionRound = 30;
	$PKConfig->atkMP = 15;
	$PKConfig->defMP = 10;
	
	//A是否克制B
	function isRestrain($a,$b){
		global $monster_kind;
		$type1 = $a->monsterData['type'];
		$type2 = $b->monsterData['type'];
		return in_array($type2,$monster_kind[$type1]['restrain'],true);
	}
	
	
	//PK一回合，直到有一单位死掉
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
		if($a->id < $b->id)
			return -1;
		if($a->id > $b->id)
			return 1;
	}
	
	//其中一个单位出手
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
		
		
		//得到要行动的单位
		
		$enemy = $user->team->enemy->currentMonster[0];
		$self = $user->team->currentMonster[0];

		$haveAction = false;//是否有行动
		$haveSkill = false;//是否有大招，由于是插播，不算行动回合

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
					$user->testTSkill('SKILL3');
					$user->testTSkill('SKILL4');
					$enemy->testTSkill('ESKILL3');
					$enemy->testTSkill('ESKILL4');
					$pkData->dealTArray();//特性生效
					break;
				}
				
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
							$user->testTSkill('SKILL2');
							$user->testTSkill('SKILL4');
							$enemy->testTSkill('ESKILL2');
							$enemy->testTSkill('ESKILL4');
							$pkData->dealTArray();//特性生效
						}
					}
					if($skillOK)
					{
						$haveAction = true;
						break;
					}
				}
				
				
				if($user->action1 <= 0 && $user->action5 <= 0)//普攻
				{
					pk_atk($user,$enemy);
					$haveAction = true;
					$user->testTSkill('SKILL1');
					$user->testTSkill('SKILL4');
					$enemy->testTSkill('ESKILL1');
					$enemy->testTSkill('ESKILL4');
					$pkData->dealTArray();//特性生效
					break;
				}
			}while(false);
			if(!$haveSkill && $user->cdhp)//当时影响血
			{
				pk_cdhp($user);
			}
			$user->testTSkill('AFTER');
			$pkData->dealTArray();//特性生效
		}
		else
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
		
		if(!$haveSkill && $user->setHaveAction($haveAction))
		{
			pk_kill($user,$enemy);
		}
		
		$user->setRoundEffect();
		if($haveSkill || $haveAction || $user->isPKing)
			$pkData->out_end($user);
			

		
		//返回PK结果
		return $pkData->testRoundFinish();
	}
?> 