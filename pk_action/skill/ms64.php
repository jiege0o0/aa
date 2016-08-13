<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//��������һָ��600%�˺�
	class sm_64_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*6);
		}
	}
	
	//������+10MP
	class sm_64_1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	
	
	//����--������+8MP
	class sm_64_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,8);
		}
	}	
	//����--����ʱ��20��MP
	class sm_64_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,20);
		}
	}

?> 