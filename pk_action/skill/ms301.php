<?php 
	require_once($filePath."pk_action/skill/skill_base.php");


	//����150%�˺�������round3
	class sm_301_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$enemy->action3 ++;
			$enemy->addState($user,array('action3'=>1),3);
		}
	}
	
	//С����70%�˺���-10������CD3
	class sm_301_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
			$this->addMp($user,$enemy,-10);
		}
	}
	
	//�أ�����ʱ���ħ�⣬5�غ�
	class sm_301_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat1 ++;
			$self->addState($user,array('stat1'=>1),5);
		}
	}
	
	//�������� round3��CD5
	class sm_301_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$enemy->action3 ++;
			$enemy->addState($user,array('action3'=>1),3);
		}
	}	
	
	//����50%�˺���CD2
	class sm_301_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 