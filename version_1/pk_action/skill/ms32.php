<?php 
	

	//技：吹风：速度-50%，持续百分比受伤10%，round3
	class sm_32_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($enemy->maxHp*0.1),3,'32_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.5),3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}
	
	//心灵之火：防-30%，round2,cd3
	class sm_32_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',-30,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//变身:当生命少于30%时，化鹰，回复50%，速+20%
	class sm_32_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$this->addSpeed($user,$user,$user->base_speed*1.2);
			$this->addAtk($user,$user,-$user->base_atk*0.5);
			$this->addHp($user,$user,$user->maxHp*0.5);
		}
	}
	
	//辅：-- 心灵之火：防-30%，round2,cd3
	class sm_32_f1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',-30,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	
	//辅：--80%伤害
	class sm_32_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}

?> 