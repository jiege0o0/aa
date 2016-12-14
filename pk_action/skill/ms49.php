<?php 
	
	
	//技：猛击（技）：100%伤害，晕一回合
	class sm_49_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//不屈：当行动后生命少于30%时，回10%血
	class sm_49_1 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.3;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	
	//辅：-- 50%伤害
	class sm_49_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//辅：--猛击：100%伤害，cd4
	class sm_49_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}

?> 