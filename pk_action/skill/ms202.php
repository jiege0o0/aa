<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：伤害+40%，round3
	class sm_202_0 extends SkillBase{
		function action($user,$self,$enemy){
			$value = $this->addHurt($user,$self,40);
			$self->addState($user,array('hurt'=>40),3);
		}
	}
	
	// 小技：150%伤害
	class sm_202_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//特：出场时回复50%最大生命
	class sm_202_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//辅：伤害+20%，round2，cd5
	class sm_202_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$value = $this->addHurt($user,$self,20);
			$self->addState($user,array('hurt'=>20),2);
		}
	}
	
	//辅：100%伤害，CD4
	class sm_202_f2 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 