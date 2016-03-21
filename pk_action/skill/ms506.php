<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����ظ���max-cur��*50%HP
	class sm_506_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,($self->maxHp - $self->hp)*0.5);
		}
	}
	
	//С����150%�˺���CD3
	class sm_506_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	
	//�����ظ���max-cur��*20%HP��CD3
	class sm_506_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,($self->maxHp - $self->hp)*0.2);
		}
	}

?> 