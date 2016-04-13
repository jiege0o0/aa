<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：出血状态（-30%），round4 120%伤
	class sm_303_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			$enemy->healAdd -= 30;
			$enemy->addState($user,array('healAdd'=>-30),4);
		}
	}
	
	//小技：120%，CD3
	class sm_303_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//小技：双方回血最大生命10%，CD3
	class sm_303_2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.1);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//辅：出血状态，round2，CD4
	class sm_303_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$enemy->healAdd -= 30;
			$enemy->addState($user,array('healAdd'=>-30),2);
		}
	}
	
	//辅：攻+10%
	class sm_303_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}

?> 