<?php 
	

	//技：缠绕：无法普攻2回合，并持续-血 (ATK*0.5)
	class sm_13_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*1.5),2,'13_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);		
			
		}
	}
	
	//回复：每3回合回自己10%血
	class sm_13_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}
	
	//木甲：天生-10%伤害
	class sm_13_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$user,15);
		}
	}
	
	
	//辅：-- 回复：7%生命
	class sm_13_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05);
		}
	}	
	//辅：-- +5%免伤
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$self,10);
		}
	}

	//辅：-- 缠绕：普攻1回合,50%伤害，cd5
	class sm_13_f3 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*1),1,'13_f3');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

?> 