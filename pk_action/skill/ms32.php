<?php 
	

	//技：吹风：速度-50%，持续百分比受伤10%，round3
	class sm_32_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($enemy->maxHp*0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.5)),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}
	
	//心灵之火：防-30%，round2,cd3
	class sm_32_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>-30),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
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
			$user->speed += round($user->base_speed*0.2);
			$this->addHp($user,$user,$user->maxHp*0.5);
		}
	}
	
	//辅：-- 心灵之火：防-30%，round2,cd3
	class sm_32_f1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>-30),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}	
	//辅：--80%伤害
	class sm_32_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}

?> 