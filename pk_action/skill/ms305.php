<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//���������и���״̬����100%�˺�Ѫ
	class sm_305_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
			$this->cleanStat($self,$enemy->teamID,999);
		}
	}
	
	//С����120%����Է�һ������״̬��CD3
	class sm_305_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			$this->cleanStat($enemy,$enemy->teamID,1);
		}
	}
	
	//�أ�-30%��ǰ����(Ĭ������������һ��)
	class sm_305_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3,false,false,true);
		}
	}
	
	//������50%�˺�Ѫ������һ������״̬��cd3
	class sm_305_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.5);
			$this->cleanStat($self,$enemy->teamID,1);
		}
	}


?> 