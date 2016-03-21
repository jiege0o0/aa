<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����160%�˺�    
	class sm_207_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//С����110%�˺���CD3
	class sm_207_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//�أ�20%����Ѫ(���ᱻ��)
	class sm_207_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat2 += 20;
		}
	}
	
	//����10%����Ѫ
	class sm_207_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat2 += 10;
		}
	}
	

?> 