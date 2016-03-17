<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：220%伤害
	class sm_106_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//小技：100%伤害，并增加10%速度，CD2
	class sm_106_1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addSpeed($user,$self,$self->base_speed*0.1);
		}
	}
	
	
	//辅：敌方全体速度-5%，不可叠加 
	class sm_106_f1 extends SkillBase{
		public $leader = true;
		public $once = true;
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$enemy,-$enemy->base_speed*0.05);
		}
	}

?> 