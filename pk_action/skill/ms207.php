<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：160%伤害    
	class sm_207_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//小技：110%伤害，CD3
	class sm_207_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//特：20%的吸血(不会被禁)
	class sm_207_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat2 += 20;
		}
	}
	
	//辅：10%的吸血
	class sm_207_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat2 += 10;
		}
	}
	

?> 