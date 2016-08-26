<?php 
	

	//技：缠绕：无法普攻2回合，并持续-血 (ATK*0.5)
	class sm_13_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.5),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
			
			
		}
	}
	
	//回复：每3回合回自己10%血
	class sm_13_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//木甲：天生-10%伤害
	class sm_13_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 10;
		}
	}
	
	
	//辅：-- 回复：7%生命
	class sm_13_f1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.07);
		}
	}	
	//辅：-- +5%免伤
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->def += 5;
		}
	}

	//辅：-- 缠绕：普攻1回合,50%伤害，cd5
	class sm_13_f3 extends SkillBase{
		public $isAtk = true;
		public $cd = 5;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.5),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 