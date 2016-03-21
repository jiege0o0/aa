<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������220%�˺�
	class sm_404_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	// С����110%�˺���20������CD3
	class sm_404_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.1);
			$this->addMp($user,$enemy,-20);
		}
	}
	
	//�أ��Է��˺�-15%
	class sm_404_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHurt($user,$enemy,-15);
		}
	}
	
	//����-10������CD3��30%�˺�
	class sm_404_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
			$this->addMp($user,$enemy,-10);
		}
	}
	


?> 