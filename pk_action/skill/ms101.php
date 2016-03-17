<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：200%伤害 
	class sm_101_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	// 小技：130%伤害，CD3
	class sm_101_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	// 特：同时出场3个，速度加成20%
	class sm_101_2 extends SkillBase{
		public $cd = 0;
		
		function canUse($user,$self=null,$enemy=null){
			if($user->team->teamInfo['num'][101] == 3)
				return true;
			return false;
		}
		function action($user,$self,$enemy){
			$value = $this->addSpeed($user,$self,$self->base_speed*0.2);
		}
	}
	
	//辅：我方全体速度+8%，不可叠加
	class sm_101_f1 extends SkillBase{
		public $leader = true;
		public $once = true;
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.08);
		}
	}

?> 