<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//����200%�˺�
	class sm_304_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	// С����150%��CD3
	class sm_304_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�أ�������
	class sm_304_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->team->stopRing = true;
		}
	}
	
	//����������
	class sm_304_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->team->stopRing = true;
		}
	}


?> 