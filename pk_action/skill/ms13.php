<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：缠绕：无法普攻2回合，并持续-血 (ATK*0.6)
	class sm_13_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.6),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
			
			
		}
	}
	
	//回复：每3回合回自己15%血
	class sm_13_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.15);
		}
	}
	
	//木甲：天生-15%伤害
	class sm_13_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 15;
		}
	}
	
	
	//辅：-- 回复：6%生命
	class sm_13_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.06);
		}
	}	
	//辅：-- +10%免伤
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 10;
		}
	}

	//辅：-- 缠绕：普攻1回合,60%伤害，cd5
	class sm_13_f3 extends SkillBase{
		public $cd = 5;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.6),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 