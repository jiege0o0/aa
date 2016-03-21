<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：回复（max-cur）*50%HP
	class sm_506_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,($self->maxHp - $self->hp)*0.5);
		}
	}
	
	//小技：150%伤害，CD3
	class sm_506_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	
	//辅：回复（max-cur）*20%HP，CD3
	class sm_506_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,($self->maxHp - $self->hp)*0.2);
		}
	}

?> 