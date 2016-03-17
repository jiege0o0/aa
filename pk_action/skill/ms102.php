<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：回复 200% 攻血量 
	class sm_102_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*2);
		}
	}
	
	// 小技：160%伤害，CD3
	class sm_102_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//特：每有一个同种族单位，生命加成5% 
	class sm_102_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['tnum'][102])
				$add = $user->team->teamInfo['tnum'][102] * 0.05;
			$this->addHp($user,$self,$self->base_hp*$add,true);
		}
	}
	
	//辅：回复 100% 攻血量，CD：4  
	class sm_102_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
		}
	}
	
	//辅：增加4%攻击  
	class sm_102_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.04);
		}
	}

?> 