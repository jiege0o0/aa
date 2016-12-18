<?php 
	

	//技：炸弹礼物：回复对方10%生命，对方回合结束时，回复值*250%伤害，round1
	class sm_39_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->addHp($user,$enemy,$enemy->maxHp*0.25);
			$buff = new HPBuff(-$v*3,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//糖果：回复对方10%生命，-10%速度，cd3
	class sm_39_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.15);
			$enemy->addSpeed(-$enemy->base_speed*0.1);
			$enemy->addAtk(-$enemy->base_atk*0.1);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//同乐：当对方回复生命时，自己回复其回复值50%生命
	class sm_39_2 extends SkillBase{
		public $type='EBEHEAL';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$this->tData*0.8);
		}
	}
	
	//无礼物了：当为对方送的礼物>5次时，每送一次礼物增加自己10%攻击力
	class sm_39_3 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			if($user->temp['sendGift'] != $this->temp)
			{
				$this->temp = $user->temp['sendGift'];
				if($this->temp > 5)
				{
					$user->addAtk($user->base_atk*0.1);
				}
			}
		}
	}
	
	//辅：--回复攻击力100%血量，5次后变成80%伤害
	class sm_39_f1 extends SkillBase{
		public $cd = 1;
		function canUse($user,$self=null,$enemy=null){
			if($this->temp1 <5)
				$this->isAtk = false;
			else
				$this->isAtk = true;
			return true;
		}
		function action($user,$self,$enemy){
			if($this->isAtk)
			{
				$this->decHp($user,$enemy,$user->atk*1);
			}
			else
			{
				$this->addHp($user,$self,$user->atk);
			}
			$this->temp1 ++;
		}
	}	

?> 