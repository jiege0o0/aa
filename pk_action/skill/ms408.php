<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：造成120%伤害，包括生命上限(永远)
	class sm_408_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2,true,true);
		}
	}
	
	// 小技：造成80%伤害，包括生命上限，CD3 
	class sm_408_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2,true);
		}
	}
	
	//特：进场时，对对方造成180%伤害
	class sm_408_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//辅：全体加5%攻击，可叠加 
	class sm_408_f1 extends SkillBase{
		public $leader = true;//总PK前执行
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.5);
		}
	}

?> 