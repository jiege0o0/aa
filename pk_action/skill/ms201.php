<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：180%伤害
	class sm_201_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	// 小技：回复200%伤害，CD5
	class sm_201_1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*2);
		}
	}
	
	// 特：出场时获得盾30%，5回合
	class sm_201_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$value = $this->addDef($user,$self,30);
			$self->addState($user,array('def'=>30),5);
		}
	}
	
	//辅：出场时获得盾30%，2回合
	class sm_201_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$value = $this->addDef($user,$self,50);
			$self->addState($user,array('def'=>50),2);
		}
	}	
	
	//辅：回复100%伤害，CD5
	class sm_201_f2 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
		}
	}

?> 