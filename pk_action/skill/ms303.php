<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������Ѫ״̬��-30%����round4 120%��
	class sm_303_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			$enemy->healAdd -= 30;
			$enemy->addState($user,array('healAdd'=>-30),4);
		}
	}
	
	//С����120%��CD3
	class sm_303_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//С����˫����Ѫ�������10%��CD3
	class sm_303_2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.1);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//������Ѫ״̬��round2��CD4
	class sm_303_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$enemy->healAdd -= 30;
			$enemy->addState($user,array('healAdd'=>-30),2);
		}
	}
	
	//������+10%
	class sm_303_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}

?> 