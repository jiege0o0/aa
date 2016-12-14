<?php 
	
	//技：水盾（技）：-20%伤害，每回合回10%血，round3
	class sm_8_0 extends SkillBase{
		function action($user,$self,$enemy){
		
			$buff = new ValueBuff('def',20,3);
			$buff->addToTarget($self);
			
			$buff = new HPBuff(round($self->maxHp*0.1),3);
			$buff->addToTarget($self);
		}
	}
	
	//猛击：+60%伤害,cd3
	class sm_8_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//水甲：当生命小于20%时，-50%伤害，round2,一次
	class sm_8_2 extends SkillBase{
		public $type = 'HP';//特性技能
		public $once = true;//技能只执行一次
		
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',50,2);
			$buff->addToTarget($self);
		}
	}

	//辅：--奇攻：奇数回合造成60%伤害
	class sm_8_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		public $order = 100;
		
		function action($user,$self,$enemy){
			$this->order --;
			$this->decHp($user,$enemy,$user->atk*1);

		}
	}	
	
	//辅：--偶回，偶数回合回复60%攻血
	class sm_8_f2 extends SkillBase{
		public $cd = 1;
		public $order = 100;
		function action($user,$self,$enemy){
			$this->order --;
			$this->addHp($user,$self,$user->atk*0.9);
		}
	}

?> 