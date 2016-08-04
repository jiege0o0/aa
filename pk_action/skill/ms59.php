<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//技：自爆(技):造成1000%伤害
	class sm_59_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*10);
			$self->dieMissTimes = array();
			$this->decHp($user,$self,999999);
		}
	}
	
	//魔免：进入时，3round
	class sm_59_1 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,3);
			$buff->addToTarget($user);
		}
	}
	
	//反射：伤害-10%
	class sm_59_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 10;
		}
	}
	
	
	//辅：--80%伤害
	class sm_59_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	

?> 