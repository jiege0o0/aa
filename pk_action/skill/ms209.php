<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����220%�˺�    
	class sm_209_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//С����+60������CD4
	class sm_209_1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,60);
		}
	}
	
	
	//����ȫ�����ʱ����+10�����ɵ���
	class sm_209_f1 extends SkillBase{
		public $leader = true;
		public $once = true;
		public $cd = 0;
		public $lRound = 999;
		
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	//����20%��Ѫ��cd1
	class sm_209_f2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.2);
		}
	}


?> 