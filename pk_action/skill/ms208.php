<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//技：回复200%伤害HP
	class sm_208_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->atk*2);
		}
	}
	
	// 小技：110%伤害，CD3  
	class sm_208_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.1);
		}
	}
	
	//特：生命回复时，对方生命-50%回复量
	class sm_208_2 extends SkillBase{
		public $type = 'BEHEAL';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$this->tData*0.5);
		}
	}
	
	//辅：100%的回血，CD3
	class sm_208_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->atk*1);
		}
	}
	

?> 