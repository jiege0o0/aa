<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：300%伤害 
	class sm_104_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	// 200%伤害，CD3  
	class sm_104_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//特：-30%当前生命，增加30%速度 
	class sm_104_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3);
			$this->addSpeed($user,$self,$self->base_speed*0.3);
		}
	}
	
	//辅：增加10%的攻  
	class sm_104_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}
	
	//辅：120%伤害，CD3 
	class sm_104_f2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}

?> 